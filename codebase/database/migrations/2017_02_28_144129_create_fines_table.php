<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fines', function(Blueprint $table)
        {
            $table->integer('loan_id')->unsigned();
            $table->integer('fine_amt')->unsigned();
            $table->boolean('paid');
            $table->primary('loan_id');
            $table->foreign('loan_id')->references('loan_id')->on('book_loans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fines');
    }
}
