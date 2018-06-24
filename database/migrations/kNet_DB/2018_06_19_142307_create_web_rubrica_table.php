<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebRubricaTable extends Migration
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
            $table->string('descrizion', 50)->comment('Ragione Sociale');
            $table->string('partiva', 17)->comment('Partita IVA');
            $table->string('codfiscale', 16)->nullable()->comment('Codice Fiscale');
            $table->string('telefono', 16)->nullable()->comment('TELEFONO');
            $table->string('telcell',16)->nullable()->comment('Telefono Cellulare');
            $table->string('persdacont', 30)->nullable()->comment('Persona da Contattare');
            $table->string('posperscon',40)->nullable()->comment('Posizione Aziendale della Persona da Contattare');
            $table->string('legalerapp', 60)->nullable()->comment('Legale Rappresentante');
            $table->string('email', 80)->comment('Email principale');
            $table->string('indirizzo', 50)->nullable()->comment('Indirizzo Sede');
            $table->string('cap',10)->nullable()->comment('CAP');
            $table->string('localita', 60)->nullable()->comment('Località');
            $table->string('prov')->nullable()->comment('Provincia');
            $table->string('regione')->nullable()->comment('Regione');
            $table->string('codnazione',3)->nullable()->comment('Codice Nazione');
            $table->string('settore',30)->nullable()->comment('Settore Merciologico');
            $table->string('statocf',1)->nullable()->comment('Stato');
            $table->string('sitoweb')->nullable()->comment('Sito Web');
            $table->string('agente',3)->nullable()->comment('Codice Agente Associato');
            $table->integer('user_id')->unsigned()->comment('Codice Utente Associato');
            $table->timestamps();
            $table->softDeletes();

            $table->unique('email');
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
