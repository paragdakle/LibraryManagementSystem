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
        Schema::table('borrower', function (Blueprint $table) {
            $table->increments('card_id');
            $table->string('ssn', 9);
            $table->string('bname');
            $table->string('email', 254);
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('phone');
            $table->unique('ssn');
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
