<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    protected $table="authors";
    protected $primary_key = "author_id";
    public $timestamps = false;
}
