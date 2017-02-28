<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Borrower extends Model
{
    protected $table = "borrower";
    protected $primary_key = "card_id";
    public $timestamps = false;
}
