<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('book_loans', function(Blueprint $table)
        {
            $table->increments('loan_id');
            $table->string('isbn', 10);
            $table->integer('card_id')->unsigned();
            $table->bigInteger('date_out')->nullable(false);
            $table->bigInteger('due_date')->nullable(false);
            $table->bigInteger('date_in')->nullable()->default(null);
            $table->foreign('isbn')->references('isbn')->on('book');
            $table->foreign('card_id')->references('card_id')->on('borrower');
        });
        Schema::table('book_loans', function (Blueprint $table) {
            $table->index('card_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('book_loans');
    }
}
