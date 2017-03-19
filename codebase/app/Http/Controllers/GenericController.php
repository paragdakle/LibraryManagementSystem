<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Book;
use App\Fine;
use App\Borrower;
use App\BookLoan;
use App\Util\CommonMethods;

use Config;

class GenericController extends Controller
{
    public function getHome(Request $request) {
        $response = array();
        $response['bk_cnt'] = Book::count();
        $response['u_cnt'] = Borrower::count();
        $response['ln_cnt'] = BookLoan::whereNull('date_in')->count();
        $response['fn_amt'] = Fine::where('paid', 0)->sum('fine_amt');
        if($response['fn_amt'] == null) {
            $response['fn_amt'] = 0;
        }
        return view('welcome', $response);
    }
}
