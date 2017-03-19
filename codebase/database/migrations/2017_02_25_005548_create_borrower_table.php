<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBorrowerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('borrower', function(Blueprint $table)
        {
            $table->increments('card_id');
            $table->string('ssn', 9)->unique()->nullable(false);
            $table->string('bname')->nullable(false);
            $table->string('email', 254)->nullable(false);
            $table->string('address')->nullable(false);
            $table->string('city')->nullable(false);
            $table->string('state')->nullable(false);
            $table->string('phone')->nullable(false);
        });
        Schema::table('borrower', function (Blueprint $table) {
            $table->index('bname');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('borrower');
    }
}
