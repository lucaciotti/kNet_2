<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateScadenzeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scadenze', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('ID Univoco');
            $table->bigInteger('id_pnota')->unsigned()->nullable()->comment('ID rif. Prima Nota');
            $table->bigInteger('id_doc')->unsigned()->nullable()->comment('ID rif. DocTes');
            $table->string('codpag', 4)->nullable()->comment('Codice Pagamento');
            $table->date('datafatt')->nullable()->comment('Data Fattura');
            $table->string('numfatt',10)->nullable()->comment('Numero Fattura');
            $table->date('datascad')->nullable()->comment('Data Scadenza');
            $table->string('codbanca',2)->nullable()->comment('Codice della Banca');
            $table->string('codcf',6)->nullable()->comment('Codice Cli Forn');
            $table->string('codag', 3)->nullable()->comment('Codice Agente');
            $table->string('tipo',1)->nullable()->comment('Tipo Scadenza: D=Rimessa Diretta; R=Ricevuta Bancaria; T=Tratta; P=Pagherò; B=Bonifico; L=Bollettino di C/C; C=Contrassegno; A=Altro');
            $table->boolean('emesso')->nullable()->comment('Effetto Emesso');
            $table->boolean('contabil')->nullable()->comment('Effetto Contabilizzato');
            $table->boolean('insoluto')->nullable()->comment('Effetto Insoluto');
            $table->boolean('u_insoluto')->nullable()->comment('Effetto Moroso');
            $table->boolean('pagato')->nullable()->comment('Effetto Pagato');
            $table->string('tipomod',2)->nullable()->comment('Tipo Documento');
            $table->double('impeff')->nullable()->comment('Importo Effetto');
            $table->double('impeffval')->nullable()->comment('Importo in Valuta');
            $table->double('imptotfatt')->nullable()->comment('Importo Totale Fattura');
            $table->double('imptotfatv')->nullable()->comment('Importo Fattura In Valuta');
            $table->double('importopag')->nullable()->comment('Importo Pagato in Valuta');
            $table->double('impprovv')->nullable()->comment('Imp. Provvigioni Valuta');
            $table->double('imponibile')->nullable()->comment('Imponibile Fattura');
            $table->string('codcambio')->nullable()->comment('Codice Cambio');
            $table->double('valcambio')->nullable()->comment('Valore di Cambio');
            $table->date('datapag')->nullable()->comment('Data Pagamento');
            $table->integer('partanno')->nullable()->comment('Anno Partita');
            $table->string('partnum',10)->nullable()->comment('Numero Partita');
            $table->string('tipoacc',1)->nullable()->comment('Tipo Accorpamento: M=Madre; F=Figlia');
            $table->bigInteger('idragg')->unsigned()->nullable()->comment('ID Scad Accorpata');
            $table->string('sollecito',3)->nullable()->comment('Codice Ultimo Sollecito');
            $table->tinyInteger('gravita')->nullable()->comment('Gravità Sollecito');
            $table->date('datasollec')->nullable()->comment('Data Ultimo Sollecito');
            $table->string('protocollo',8)->nullable()->comment('Protocollo del Documento');
            $table->double('impant')->nullable()->comment('Importo Anticipato');
            $table->date('dataant')->nullable()->comment('Data Anticipo');
            $table->date('datascant')->nullable()->comment('Data Scadenza Anticipo');
            $table->timestamp('timestamp')->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('scadenze');
    }
}
