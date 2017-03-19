<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'GenericController@getHome');

//Book Controller Routes
Route::get('/books', 'BookController@getBooksView');

Route::get('/api/books', 'BookController@getBooks');

Route::get('/book', 'BookController@getBookView');

Route::get('/book/search', 'BookController@searchBooksView');

Route::get('/api/book/search', 'BookController@searchBooks');

Route::post('/api/book/loan', 'BookController@loanBook');

Route::post('/api/book/return', 'BookController@returnBooks');

Route::get('/bookloans/search', 'BookController@searchLoansView');

Route::get('/api/bookloans/search', 'BookController@searchLoans');

//Borrower Routes
Route::get('/users', 'BorrowerController@getBorrowersView');

Route::get('/user/add', function() {
    return view('adduser');
});

Route::post('/api/borrower/add', 'BorrowerController@addBorrower');

Route::get('/api/borrowers', 'BorrowerController@getBorrowers');

Route::get('/borrower', 'BorrowerController@getBorrowerView');

Route::get('/api/borrower', 'BorrowerController@getBorrower');

Route::post('/api/borrower/payfine', 'BorrowerController@payFines');