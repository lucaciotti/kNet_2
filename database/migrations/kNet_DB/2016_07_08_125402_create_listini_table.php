<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateListiniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listini', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('');
            $table->string('codicearti',20)->nullable()->comment('');
            $table->string('codclifor',6)->nullable()->comment('');
            $table->string('gruppomag',5)->nullable()->comment('');
            $table->string('gruppocli',3)->nullable()->comment('');
            $table->double('prezzo')->nullable()->comment('');
            $table->string('sconto',12)->nullable()->comment('');
            $table->date('datainizio')->nullable()->comment('');
            $table->date('datafine')->nullable()->comment('');
            $table->string('codvaluta',3)->nullable()->comment('');
            $table->double('quantita')->nullable()->comment('');
            $table->double('u_budg1')->nullable()->comment('');
            $table->double('u_budg2')->nullable()->comment('');
            $table->double('u_budg3')->nullable()->comment('');
            $table->string('u_budg1p',8)->nullable()->comment('');
            $table->string('u_budg2p',8)->nullable()->comment('');
            $table->string('u_budg3p',8)->nullable()->comment('');
            $table->double('u_budg1n')->nullable()->comment('');
            $table->double('u_budg2n')->nullable()->comment('');
            $table->double('u_budg3n')->nullable()->comment('');
            $table->string('listino',1)->nullable()->comment('');
            $table->boolean('u_noprzmin')->nullable()->comment('');
            $table->boolean('u_soloce')->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('listini');
    }
}
