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

Route::get('/', function () {
    return view('welcome');
});

//Book Controller Routes
Route::get('/book', 'BookController@getBooks');

Route::get('/book/search', 'BookController@searchBooks');

Route::post('/book/loan', 'BookController@loanBook');

Route::post('/book/return', 'BookController@returnBooks');
