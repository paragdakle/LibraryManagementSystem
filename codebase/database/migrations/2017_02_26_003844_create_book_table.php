<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book', function(Blueprint $table)
        {
            $table->string('isbn', 10);
            $table->string('isbn13', 13)->unique()->nullable(false);
            $table->string('title')->nullable(false);
            $table->string('cover')->nullable()->default(null);
            $table->string('publisher')->nullable()->default(null);
            $table->integer('pages')->nullable(false)->default(0);
            $table->primary('isbn');
        });
        Schema::table('book', function (Blueprint $table) {
            $table->index('title');
            $table->index('isbn13');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('book');
    }
}
