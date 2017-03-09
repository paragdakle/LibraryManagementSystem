<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Book;
use App\Util\CommonMethods;

use Config;

class BookController extends Controller
{
    public function getBooks(Request $request, $pageNumber)
    {
        if(!is_int($pageNumber)) {
            return CommonMethods::generateErrorResponse("Invalid API Call", Config::get('errorcodes.HTTP_BAD_REQUEST_STATUS_CODE'));
        }
        $startRecordNumber = Config::get('constants.book_page_size') * ($pageNumber - 1);
        $endRecordNumber = $startRecordNumber + Config::get('constants.book_page_size');
        try {
            $books = Book::orderBy('title', 'asc')->limit(Config::get('constants.book_page_size'))->skip($startRecordNumber)->get();
            return CommonMethods::generateSuccessResponse($books);
        }
        catch(Exception $e)
        {
            return CommonMethods::generateErrorResponse("Internal Server Error", Config::get('errorcodes.HTTP_INTERNAL_SERVER_ERROR'));
        }
    }

    public function searchBook(Request $request, $searchTerm) {

    }
}
