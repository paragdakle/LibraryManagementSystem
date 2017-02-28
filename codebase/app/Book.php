<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table="book";
    protected $primary_key = "isbn";
    public $timestamps = false;
}
