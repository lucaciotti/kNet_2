<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUlistiniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_listin', function (Blueprint $table) {
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('u_listin');
    }
}
