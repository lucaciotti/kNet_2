<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CretaeWebRubricaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_rubrica', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('descrizion', 50)->nullable()->comment('Ragione Sociale');
            $table->string('partiva', 17)->nullable()->comment('Partita IVA');
            $table->string('codfiscale', 16)->nullable()->comment('Codice Fiscale');
            $table->string('telefono', 16)->nullable()->comment('TELEFONO');
            $table->string('telcell',16)->nullable()->comment('Telefono Cellulare');
            $table->string('persdacont', 30)->nullable()->comment('Persona da Contattare');
            $table->string('posperscon',40)->nullable()->comment('Posizione Aziendale della Persona da Contattare');
            $table->string('legalerapp', 60)->nullable()->comment('Legale Rappresentante');
            $table->string('email', 80)->nullable()->comment('Email principale');
            $table->string('indirizzo', 50)->nullable()->comment('Indirizzo Sede');
            $table->string('cap', 5)->nullable()->comment('CAP');
            $table->string('localita', 40)->nullable()->comment('Località');
            $table->string('prov',2)->nullable()->comment('Provincia');
            $table->string('codnazione',3)->nullable()->comment('Codice Nazione');
            $table->string('settore',3)->nullable()->comment('Settore Merciologico');
            $table->string('statocf',1)->nullable()->comment('Stato');
            $table->string('sitoweb')->nullable()->comment('Sito Web');
            $table->integer('agente',3)->nullable()->comment('Codice Agente Associato');
            $table->integer('user_id')->comment('Codice Utente Associato');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('w_rubrica');
    }
}
