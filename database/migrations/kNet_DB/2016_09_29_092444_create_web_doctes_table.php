<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebDoctesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_doctes', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID univoco per ogni documento');
            $table->string('esercizio',4)->nullable()->comment('Esercizio Contabile/Magazzino');
            $table->string('tipodoc',2)->nullable()->comment('Codice Documento');
            $table->string('numerodoc',2)->nullable()->comment('Numero Progressivo per Tipo Documento');
            $table->date('datadoc')->nullable()->comment('Data Emissione Documento');
            $table->string('codicecf',6)->nullable()->comment('Codice Cliente / Fornitore');
            $table->string('magpartenz',5)->nullable()->comment('Magazzino di Partenza (xMagMov)');
            $table->string('magarrivo',5)->nullable()->comment('Magazzino di Arrivo (xMagMov)');
            $table->integer('numrighepr')->nullable()->comment('Numero di Righe da Prelevare');
            $table->string('agente',3)->nullable()->comment('Codice Agente associato al Documento di Vendita');
            $table->string('valuta',3)->nullable()->comment('Codice Valuta');
            $table->string('pag',4)->nullable()->comment('Codice Tipologia Pagamento');
            $table->string('sconti',10)->nullable()->comment('Sconto Merce supplementare');
            $table->string('scontocass',10)->nullable()->comment('Sconto Cassa sul pagamento');
            $table->double('cambio')->nullable()->comment('Valore di Cambio Valuta');
            $table->date('datadocfor')->nullable()->comment('Data Documento Fornitore');
            $table->string('numerodocf',15)->nullable()->comment('Riferimento Documento Fornitore');
            $table->string('tipomodulo',1)->nullable()->comment('Tipologia Documento');
            $table->string('destdiv',4)->nullable()->comment('Destinazione Diversa Merce');
            $table->double('pesolordo')->nullable()->comment('Peso Lordo kg');
            $table->double('pesonetto')->nullable()->comment('Peso Netto kg');
            $table->double('volume')->nullable()->comment('Volume mq');
            $table->string('vettore1',4)->nullable()->comment('Codice Vettore');
            $table->date('v1data')->nullable()->comment('Data Presa in Carico Vettore');
            $table->string('v1ora',5)->nullable()->comment('Ora Presa in Carico Vettore');
            $table->string('colli',9)->nullable()->comment('Numero di Colli');
            $table->string('aspbeni',3)->nullable()->comment('Codice Aspetto Beni');
            $table->double('speseim')->nullable()->comment('Spese Trasporto a Carico del Cliente');
            $table->string('speseimiva',3)->nullable()->comment('Iva Su Spese Trasporto');
            $table->double('u_spesetra')->nullable()->comment('Spese Trasporto a Carico Mittente');
            $table->double('totimp')->nullable()->comment('Valore Totale Imponibile');
            $table->double('totdoc')->nullable()->comment('Valore Totale del Documento Comprensivo di Sconto Merce (da Ottobre 2015 no di Sconto Cassa)');
            $table->double('totiva')->nullable()->comment('Valore Totale Iva');
            $table->double('totmerce')->nullable()->comment('Valore Totale sola Merce');
            $table->double('totsconto')->nullable()->comment('Valore Totale Sconto');
            $table->boolean('nomodifica')->nullable()->comment('Documento Bloccato?');
            $table->boolean('mailed')->nullable()->comment('Inviato Documento per Mail?');
            $table->string('u_tipocons',2)->nullable()->comment('Tipologia di Consegna');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('w_doctes');
    }
}
