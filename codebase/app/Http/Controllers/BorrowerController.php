<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Borrower;
use App\BookLoan;
use App\Fine;
use App\Util\CommonMethods;

use Config;
use DB;

class BorrowerController extends Controller
{

    public function addBorrower(Request $request) {
        if(array_key_exists('ssn', $request->input())
        && array_key_exists('name', $request->input())
        && array_key_exists('email', $request->input())
        && array_key_exists('address', $request->input())
        && array_key_exists('city', $request->input())
        && array_key_exists('state', $request->input())
        && array_key_exists('phone', $request->input())) {
            $ssn = $request->input('ssn');
            $bname = $request->input('name');
            $email = $request->input('email');
            $address = $request->input('address');
            $city = $request->input('city');
            $state = $request->input('state');
            $phone = $request->input('phone');
            if(count(Borrower::where('ssn', $ssn)->orWhere('email', $email)->orWhere('phone', $phone)->get()) > 0) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.USER_ALREADY_EXISTS'), "User already exists!");
            }
            try {
                $borrower = new Borrower;
                $borrower->ssn = $ssn;
                $borrower->bname = $bname;
                $borrower->email = $email;
                $borrower->address = $address;
                $borrower->city = $city;
                $borrower->state = $state;
                $borrower->phone = $phone;
                $borrower->save();
                return CommonMethods::generateSuccessResponse("Borrower created successfully");
            }
            catch(Exception $e) {
                return CommonMethods::generateErrorResponseWithArray("Internal Server Error", Config::get('errorcodes.HTTP_INTERNAL_SERVER_ERROR'));
            }
        }
        else {
            return CommonMethods::generateErrorResponseWithArray("Invalid API Call", Config::get('errorcodes.HTTP_BAD_REQUEST_STATUS_CODE'));
        }
    }
    
    public function getBorrowers(Request $request) {
        try {
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
            $borrowers = Borrower::orderBy('bname', 'asc')
                ->limit(Config::get('constants.book_page_size'))
                ->skip($startRecordNumber)
                ->get();
            return CommonMethods::generateSuccessResponse($borrowers);
        }
        catch(Exception $e) {
            return CommonMethods::generateErrorResponseWithArray("Internal Server Error", Config::get('errorcodes.HTTP_INTERNAL_SERVER_ERROR'));
        }
    }

    public function getBorrowersView(Request $request) {
        try {
            $pageNumber = 1;
            if(array_key_exists('page', $request->input())) {
                $pageNumber = $request->input('page');
            }
            $response = $this->getBorrowers($request);
            $response = json_decode($response->content(), TRUE);
            return view('users', ['page_number' => $pageNumber, 'results' => $response['data']]);
        }
        catch(Exception $e) {
            return CommonMethods::generateErrorResponseWithArray("Internal Server Error", Config::get('errorcodes.HTTP_INTERNAL_SERVER_ERROR'));
        }
    }

    public function getBorrower(Request $request) {
        $key = "";
        $value = "";
        if(array_key_exists('card_id', $request->input())) {
            $key = 'card_id';
            $value = $request->input($key);
        }
        elseif(array_key_exists('phone', $request->input())) {
            $key = 'phone';
            $value = $request->input($key);
        }
        elseif(array_key_exists('email', $request->input())) {
            $key = 'email';
            $value = $request->input($key);
        }
        else {
            return CommonMethods::generateErrorResponseWithArray("Invalid API Call", Config::get('errorcodes.HTTP_BAD_REQUEST_STATUS_CODE'));
        }
        $borrower = Borrower::where($key, $value)->first();
        if($borrower) {
            $book_loans = BookLoan::where('card_id', $borrower->card_id)->orderBy('date_out', 'desc')->get();
            $borrower->loan_history = $book_loans;
            $this->updateFines();
            $fines = DB::table('fines as f')
                        ->join('book_loans as bl', 'f.loan_id', '=', 'bl.loan_id')
                        ->select('f.*', 'bl.isbn')
                        ->where('bl.card_id', $borrower->card_id)
                        ->orderBy('bl.date_out', 'desc')
                        ->get();
            $borrower->fine_history = $fines;
            return CommonMethods::generateSuccessResponse($borrower);
        }
        else {
            return CommonMethods::generateErrorResponse(Config::get('errorcodes.USER_NOT_FOUND'), "User not found!");
        }
    }

    private function updateFines() {
        Fine::where('paid', 0)->delete();
        $today_timestamp = strtotime('today');
        $book_loans = BookLoan::whereNull('date_in')->where('due_date', '<', $today_timestamp)->get();
        foreach($book_loans as $book_loan) {
            $time_diff = $today_timestamp - $book_loan->due_date;
            $hours = floor($time_diff / (60 * 60 * 24));
            if($hours > 0) {
                $fine_amount = $hours * Config::get('constants.fine_per_day');
                $fine = new Fine;
                $fine->loan_id = $book_loan->loan_id;
                $fine->fine_amt = $fine_amount;
                $fine->paid = false;
                $fine->save();
            }
        }
    }

    public function getBorrowerView(Request $request) {
        try {
            $response = $this->getBorrower($request);
            $response = json_decode($response->content(), TRUE);
            $fineTotal = 0;
            if(array_key_exists('fine_history', $response['data'])) {
                foreach ($response['data']['fine_history'] as $fine) {
                    if($fine['date_paid'] == NULL) {
                        $fineTotal += $fine['fine_amt'];
                    }
                }
            }
            return view('profile', ['user' => $response['data'], 'fine_total' => $fineTotal]);
        }
        catch(Exception $e) {
            return CommonMethods::generateErrorResponseWithArray("Internal Server Error", Config::get('errorcodes.HTTP_INTERNAL_SERVER_ERROR'));
        }
    }

    public function payFines(Request $request) {
        if(array_key_exists('card_id', $request->input())) {
            $card_id = $request->input('card_id');
            if(Borrower::where('card_id', $card_id)->first() == null) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.USER_NOT_FOUND'), "User not found!");
            }
            if(array_key_exists('loan_ids', $request->input())) {
                $loan_ids = $request->input('loan_ids');
                if(count($loan_ids) > 0) {
                    $book_loans = BookLoan::whereIn('loan_id', $loan_ids)
                                    ->where('card_id', $card_id)
                                    ->whereNotNull('date_in')
                                    ->get();
                    if(count($book_loans) == count($loan_ids)) {
                        Fine::whereIn('loan_id', $loan_ids)->where('paid', 0)->update(['paid' => TRUE, 'date_paid' => strtotime('today')]);
                        return CommonMethods::generateSuccessResponse("Fines cleared!");
                    }
                    else {
                        return CommonMethods::generateErrorResponse(Config::get('errorcodes.INVALID_OPERATION'), "User has not borrowed or returned one or more of the given books!");
                    }
                }
            }
            $book_loans = BookLoan::where('card_id', $card_id)->whereNull('date_in')->get();
            if(count($book_loans) > 0) {
                return CommonMethods::generateErrorResponse(Config::get('errorcodes.INVALID_OPERATION'), "User has not returned one or more borrowed books!");
            }
            DB::table('fines as f')
                ->join('book_loans as bl', 'f.loan_id', '=', 'bl.loan_id')
                ->where('bl.card_id', $card_id)
                ->where('f.paid', 0)
                ->update(['paid' => 1, 'date_paid' => strtotime('today')]);
            return CommonMethods::generateSuccessResponse("Fines cleared!");
        }
        else {
            return CommonMethods::generateErrorResponseWithArray("Invalid API Call", Config::get('errorcodes.HTTP_BAD_REQUEST_STATUS_CODE'));
        }
    }
}
