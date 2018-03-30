<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnagrafeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anagrafe', function (Blueprint $table) {
            $table->string('codice', 6)->primary()->comment('Codice Univoco');
            $table->string('descrizion', 50)->nullable()->comment('Ragione Sociale');
            $table->string('supragsoc', 40)->nullable()->comment('Supplemento Rag. Soc.');
            $table->string('partiva', 17)->nullable()->comment('Partita IVA');
            $table->string('codfiscale', 16)->nullable()->comment('Codice Fiscale');
            $table->string('fax', 16)->nullable()->comment('FAX');
            $table->string('telefono', 16)->nullable()->comment('TELEFONO');
            $table->string('telcell',16)->nullable()->comment('Telefono Cellulare');
            $table->string('persdacont', 30)->nullable()->comment('Persona da Contattare');
            $table->string('posperscon',40)->nullable()->comment('Posizione Aziendale della Persona da Contattare');
            $table->string('legalerapp', 60)->nullable()->comment('Legale Rappresentante');
            $table->string('email', 80)->nullable()->comment('Email principale');
            $table->string('emailam',80)->nullable()->comment('Email Amministrazione');
            $table->string('emailut',80)->nullable()->comment('Email Invio Ordini');
            $table->string('emailav',80)->nullable()->comment('Email Invio Bolle');
            $table->string('emailpec',80)->nullable()->comment('Email Invio Fatture');
            $table->string('indirizzo', 50)->nullable()->comment('Indirizzo Sede');
            $table->string('cap', 5)->nullable()->comment('CAP');
            $table->string('localita', 40)->nullable()->comment('Località');
            $table->string('prov',2)->nullable()->comment('Provincia');
            $table->string('zona',3)->nullable()->comment('Zona');
            $table->string('codnazione',3)->nullable()->comment('Codice Nazione');
            $table->string('lingua',3)->nullable()->comment('Lingua Madre');
            $table->string('agente',3)->nullable()->comment('Codice Agente Associato');
            $table->string('settore',3)->nullable()->comment('Settore Merciologico');
            $table->string('classe',3)->nullable()->comment('Classe Cliente');
            $table->string('pag',4)->nullable()->comment('Tipo Pagamento Default');
            $table->string('valuta',3)->nullable()->comment('Valuta Pagamenti');
            $table->double('fido')->nullable()->comment('Fido Concesso');
            $table->string('sollecito',3)->nullable()->comment('Codice Sollecito');
            $table->string('statocf',1)->nullable()->comment('Stato');
            $table->boolean('cee')->nullable()->comment('UE?');
            $table->string('gruppolist',3)->nullable()->comment('Gruppo Sconto Listino');
            $table->string('destdiv',4)->nullable()->comment('Destinazione Diversa Merce Default');
            $table->string('sitoweb')->nullable()->comment('Sito Web');
            $table->bigInteger('idrubrica')->nullable()->unsigned()->comment('Id collegamento tabella rubrica');
            $table->boolean('nascosto')->nullable()->comment('Nascondere dal browser');
            $table->date('u_dataini')->nullable()->comment('Data inizio rapporto');
            $table->date('u_datafine')->nullable()->comment('Data fine rapporto');
            $table->boolean('u_pefc')->nullable()->comment('Richiede Certificazione PEFC');
            $table->boolean('u_noce')->nullable()->comment('Fornisce Merce ExtraUE');
            $table->boolean('u_soloce')->nullable()->comment('Richiede Solo Merce di Origine UE');
            $table->boolean('u_kro')->nullable()->comment('Cliente KRONA');
            $table->boolean('u_kao')->nullable()->comment('Cliente KAO');
            $table->boolean('u_kob')->nullable()->comment('Cliente KOBLENZ');
            $table->boolean('u_grass')->nullable()->comment('Cliente GRASS');
            $table->text('note')->nullable()->comment('Note Varie');
            $table->timestamp('timestamp')->nullable()->comment('Data Ultima modifica Arca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('anagrafe');
    }
}
