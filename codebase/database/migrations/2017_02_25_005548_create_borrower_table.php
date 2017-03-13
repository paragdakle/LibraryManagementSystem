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
            $table->string('email', 254)->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('city')->nullable()->default(null);
            $table->string('state')->nullable()->default(null);
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
