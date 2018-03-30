<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocrigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('docrig', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('ID Univoco per ogni Riga Doc');
            $table->bigInteger('id_testa')->unsigned()->nullable()->comment('ID di Riferimento al Documento che la ha generata (doctes)');
            $table->integer('numeroriga')->unsignad()->nullable()->comment('Numero Progressivo Riga nel Documento');
            $table->string('codicearti',20)->nullable()->comment('Codice Articolo Movimentato');
            $table->string('descrizion', 50)->nullable()->comment('Descrizione della Riga o Articolo');
            $table->string('espldistin',1)->nullable()->comment('Carattere di Controllo nel Caso di un Esplosione Distinta (Comp. - Padre)');
            $table->string('unmisura',2)->nullable()->comment('Unita di Misura utilizzata');
            $table->double('fatt')->nullable()->comment('Fattore di Conversione');
            $table->double('quantita')->nullable()->comment('Quantità movimentata');
            $table->double('quantitare')->nullable()->comment('Quantita residua');
            $table->string('sconti')->nullable()->comment('Sconto Applicato sulla riga');
            $table->double('prezzoun')->nullable()->comment('Prezzo Unitario');
            $table->double('prezzotot')->nullable()->comment('Prezzo Totale Comprensivo di Sconto Riga');
            $table->string('aliiva',3)->nullable()->comment('Codice Iva');
            $table->boolean('omiva')->nullable()->comment('Omaggio Iva');
            $table->boolean('ommerce')->nullable()->comment('Omaggio Merce');
            $table->boolean('u_maggprz')->nullable()->comment('Flag per Maggiorazione Prezzo Applicata');
            $table->string('provv',9)->nullable()->comment('Provvigione Agente');
            $table->string('gruppo',5)->nullable()->comment('Gruppo Prodotto');
            $table->string('commessa',12)->nullable()->comment('Codice Commessa');
            $table->string('lotto',20)->nullable()->comment('Codice Lotto Articolo Movimentato');
            $table->string('matricola', 20)->nullable()->comment('Matricola descrittiva');
            $table->date('dataconseg')->nullable()->comment('Data di Consegna');
            $table->date('u_dtespld')->nullable()->comment('Data Esplodi Distinta');
            $table->date('u_dtpronto')->nullable()->comment('Data Pronto Merce');
            $table->string('rifstato',1)->nullable()->comment('Codice Stato Riga');
            $table->integer('u_misural')->nullable()->comment('Larghezza');
            $table->integer('u_misurah')->nullable()->comment('Altezza');
            $table->integer('u_misuras')->nullable()->comment('Spessore');
            $table->integer('u_casssx')->nullable()->comment('Misura Cassa sx');
            $table->integer('u_cassdx')->nullable()->comment('Misura Cassa dx');
            $table->integer('u_ingoml')->nullable()->comment('Lunghezza Ingombro');
            $table->integer('u_ingomh')->nullable()->comment('Altezza Ingombro');
            $table->integer('u_traverso')->nullable()->comment('Mirusa Traverso');
            $table->integer('u_pannl')->nullable()->comment('Lunghezza Pannello');
            $table->integer('u_pannh')->nullable()->comment('Altezza Pannello');
            $table->integer('u_panns')->nullable()->comment('Spesso Pannello');
            $table->boolean('u_fm')->nullable()->comment('Flag Fuori Misura');
            $table->string('u_maggfm',10)->nullable()->comment('% Applicata Per Maggiorazione Prezzo');
            $table->text('fogliomis')->nullable()->comment('XML Rotella Config.');
            $table->string('u_cliven',6)->nullable()->comment('Personalizzazione Cliente');
            $table->string('u_destcli',4)->nullable()->comment('Person. Cliente su Dest. Div.');
            $table->boolean('flrnc')->nullable()->comment('Flag Rnc');
            $table->bigInteger('rncid')->unsigned()->nullable()->comment('ID RNC');
            $table->text('note')->nullable()->comment('Note Varie');
            $table->bigInteger('riffromt')->unsigned()->nullable()->comment('Riferimento ID doctes prelevata');
            $table->bigInteger('riffromr')->unsigned()->nullable()->comment('Riferimento ID docrig Prelevata');
            $table->timestamp('timestamp')->nullable()->comment('Data Ultima Modifica Arca');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('docrig');
    }
}
