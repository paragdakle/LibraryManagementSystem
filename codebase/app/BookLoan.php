<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BookLoan extends Model
{
    protected $table="book_loans";
    protected $primary_key = "loan_id";
    public $timestamps = false;
}
