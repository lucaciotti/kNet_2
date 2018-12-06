<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePromo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_promo', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('');
            $table->string('codicearti',20)->nullable()->comment('');
            $table->string('descrizion',40)->nullable()->comment('Descrizione PROMO');
            $table->double('prezzo')->nullable()->comment('');
            // $table->string('sconto',12)->nullable()->comment('');
            $table->date('dataini')->nullable()->comment('');
            $table->date('datafine')->nullable()->comment('');
            $table->double('u_budg1')->nullable()->comment('');
            $table->double('u_budg2')->nullable()->comment('');
            $table->double('u_budg3')->nullable()->comment('');
            $table->string('u_budg1p',8)->nullable()->comment('');
            $table->string('u_budg2p',8)->nullable()->comment('');
            $table->string('u_budg3p',8)->nullable()->comment('');
            $table->double('u_budg1n')->nullable()->comment('');
            $table->double('u_budg2n')->nullable()->comment('');
            $table->double('u_budg3n')->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_promo');
    }
}
