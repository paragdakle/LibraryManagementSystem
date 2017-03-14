<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Book;
use App\Author;
use App\Borrower;
use App\BookLoan;
use App\Util\CommonMethods;

use Config;
use DB;

class BookController extends Controller
{
    public function getBooks(Request $request)
    {
        $pageNumber = $request->input('page');
        if(!is_int($pageNumber)) {
            try {
                $pageNumber = intval($pageNumber);
            }
            catch(Exception $e) {
                return CommonMethods::generateErrorResponseWithArray("Invalid API Call", Config::get('errorcodes.HTTP_BAD_REQUEST_STATUS_CODE'));
            }
        }
        $startRecordNumber = Config::get('constants.book_page_size') * ($pageNumber - 1);
        $endRecordNumber = $startRecordNumber + Config::get('constants.book_page_size');
        try {
            $books = DB::table('book_authors')
                ->join('authors', 'authors.author_id', '=', 'book_authors.author_id')
                ->join('book', 'book.isbn', '=', 'book_authors.isbn')
                ->select('name as authors', 'book.*')
                ->orderBy('book.title', 'asc')
                ->limit(Config::get('constants.book_page_size'))
                ->skip($startRecordNumber)
                ->get();
            return CommonMethods::generateSuccessResponse($this->getUniqueBooks($books));
        }
        catch(Exception $e)
        {
            return CommonMethods::generateErrorResponseWithArray("Internal Server Error", Config::get('errorcodes.HTTP_INTERNAL_SERVER_ERROR'));
        }
    }

    public function searchBooks(Request $request) 
    {
        $searchTerm = $request->input('term');
        $pageNumber = 1;
        if($request->input('page')) {
            $pageNumber = $request->input('page');
        }
        $startRecordNumber = Config::get('constants.book_page_size') * ($pageNumber - 1);
        $endRecordNumber = $startRecordNumber + Config::get('constants.book_page_size');
        $totalBooks = array();
        try {
            $books = DB::table('book_authors')
                ->join('authors', 'authors.author_id', '=', 'book_authors.author_id')
                ->join('book', 'book.isbn', '=', 'book_authors.isbn')
                ->select('name as authors', 'book.*')
                ->where('book.title', 'like', '%' . $searchTerm . '%')
                ->orWhere('book.isbn', 'like', '%' . $searchTerm . '%')
                ->orWhere('book.isbn13', 'like', '%' . $searchTerm . '%')
                ->orWhere('book.publisher', 'like', '%' . $searchTerm . '%')
                ->orWhere('name', 'like', '%' . $searchTerm . '%')
                ->orderBy('book.title', 'asc')
                ->limit(Config::get('constants.book_page_size'))
                ->skip($startRecordNumber)
                ->get();
            return CommonMethods::generateSuccessResponse($this->getUniqueBooks($books));
        }
        catch(Exception $e)
        {
            return CommonMethods::generateErrorResponseWithArray("Internal Server Error", Config::get('errorcodes.HTTP_INTERNAL_SERVER_ERROR'));
        }   
    }

    private function getUniqueBooks($books) {
        $uniqueBooks = array();
        if($books) {
            foreach($books as $book) {
                if(array_key_exists($book->isbn, $uniqueBooks)) {
                    $uniqueBooks[$book->isbn]->authors = $uniqueBooks[$book->isbn]->authors . ', ' . $book->authors;
                }
                else {
                    $uniqueBooks[$book->isbn] = $book;
                }
            }
        }
        return array_values($uniqueBooks);
    }

    public function loanBook(Request $request) {
        if(array_key_exists('isbn', $request->input()) && array_key_exists('card_id', $request->input())) {
            $isbn = $request->input('isbn');
            $card_id = $request->input('card_id');
            $book = Book::where('isbn', $isbn)->first();
            if($book == null) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.BOOK_NOT_FOUND'), "Book not found!");
            }
            if(Borrower::where('card_id', $card_id)->first() == null) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.USER_NOT_FOUND'), "User not found!");
            }
            $book_loans = BookLoan::where('isbn', '=', $isbn)->whereNull('date_in')->get();
            if(count($book_loans) > 0) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.BOOK_ALREADY_LOANED'), "Book has already been loaned");
            }
            $fines = DB::table('book_loans as bl')
                ->join('fines as f', 'f.loan_id' , '=', 'bl.loan_id')
                ->where('bl.card_id', '=', $card_id)
                ->where('f.paid', '=', '0')
                ->get();
            if(count($fines) > 0) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.USER_FINE_PENDING'), "User has fines due!");
            }
            $book_loans = BookLoan::where('card_id', '=', $card_id)->whereNull('date_in')->get();
            if(count($book_loans) >= Config::get('constants.max_borrower_quota')) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.USER_QUOTA_FULL'), "User qouta full!");
            }
            DB::transaction(function() use($card_id, $isbn) {
                $book_loan = new BookLoan;
                $book_loan->card_id = $card_id;
                $book_loan->isbn = $isbn;
                $book_loan->date_out = strtotime('today');
                $max_borrow_duration_days = Config::get('constants.max_borrow_duration');
                $book_loan->due_date = strtotime('+' . $max_borrow_duration_days . ' day', strtotime('today'));
                $book_loan->save();
                Book::where('isbn', $isbn)->decrement('copies_avl');
            });
            return CommonMethods::generateSuccessResponse("Book Loaned Successfully!");
        }
        else {
            return CommonMethods::generateErrorResponseWithArray("Invalid API Call", Config::get('errorcodes.HTTP_BAD_REQUEST_STATUS_CODE'));
        }
    }

    public function returnBooks(Request $request) {
        if(array_key_exists('isbns', $request->input()) 
           && array_key_exists('card_id', $request->input())) {
            $isbns = json_decode($request->input('isbns'), true);
            $card_id = $request->input('card_id');
            if(count($isbns) > Config::get('constants.max_borrower_quota')) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.INVALID_OPERATION'), "Cannot return more books than max borrow quota!");
            }
            if(count(Book::whereIn('isbn', $isbns)->get()) != count($isbns)) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.BOOK_NOT_FOUND'), "One or more of the given books was not found!");
            }
            if(Borrower::where('card_id', $card_id)->first() == null) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.USER_NOT_FOUND'), "User not found!");
            }
            $book_loans = BookLoan::whereIn('isbn', $isbns)
                            ->where('card_id', $card_id)
                            ->whereNull('date_in')
                            ->get();
            if(count($book_loans) != count($isbns)) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.INVALID_OPERATION'), "User has not borrowed one or more of the given books!");
            }
            $book_loans = BookLoan::whereIn('isbn', $isbns)->whereNotNull('date_in')->get();
            if(count($book_loans) > 0) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.BOOK_ALREADY_RETURNED'), "One or more of the given books has already been returned!");
            }
            $fines = DB::table('book_loans as bl')
                ->join('fines as f', 'f.loan_id' , '=', 'bl.loan_id')
                ->where('bl.card_id', '=', $card_id)
                ->where('f.paid', '=', '0')
                ->get();
            if(count($fines) > 0) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.USER_FINE_PENDING'), "User has fines due!");
            }
            try {
                DB::transaction(function() use($card_id, $isbns) {
                    BookLoan::whereIn('isbn', $isbns)
                        ->where('card_id', $card_id)
                        ->update(['date_in' => strtotime('today')]);
                    Book::whereIn('isbn', $isbns)->increment('copies_avl');
                });
                return CommonMethods::generateSuccessResponse("Books returned successfully!");
            }
            catch(Exception $e) {
                return CommonMethods::generateErrorResponseWithArray("Internal Server Error", Config::get('errorcodes.HTTP_INTERNAL_SERVER_ERROR'));
            }
        }
        else {
            return CommonMethods::generateErrorResponseWithArray("Invalid API Call", Config::get('errorcodes.HTTP_BAD_REQUEST_STATUS_CODE'));
        }
    }
}
