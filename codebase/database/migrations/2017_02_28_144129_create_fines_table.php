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
            $table->decimal('fine_amt', 7, 2)->unsigned()->nullable(false);
            $table->boolean('paid')->default(false);
            $table->bigInteger('date_paid')->nullable()->default(null);
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
