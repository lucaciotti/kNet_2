<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVettoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vettori', function (Blueprint $table) {
            $table->string('codice', 4)->primary()->comment('Codice Univoco');
            $table->string('descrizion',40)->nullable()->comment('Ragione Sociale Vettore');
            $table->string('indirizzo',25)->nullable()->comment('Indirizzo');
            $table->string('cap',5)->nullable()->comment('CAP');
            $table->string('localita',25)->nullable()->comment('Località');
            $table->string('pv',2)->nullable()->comment('Provincia');
            $table->string('telefono',16)->nullable()->comment('Telefono');
            $table->string('fax',16)->nullable()->comment('Fax');
            $table->string('email',50)->nullable()->comment('Email');
            $table->string('fornitore',6)->nullable()->comment('Fornitore Collegato');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('vettori');
    }
}
