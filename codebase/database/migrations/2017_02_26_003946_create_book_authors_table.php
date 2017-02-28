<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookAuthorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_authors', function(Blueprint $table)
        {
            $table->integer('author_id')->unsigned();
            $table->string('isbn', 10);
            $table->primary(array('author_id', 'isbn'));
            $table->foreign('author_id')->references('author_id')->on('authors');
            $table->foreign('isbn')->references('isbn')->on('book');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('book_authors');
    }
}
