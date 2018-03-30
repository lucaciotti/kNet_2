<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magart', function (Blueprint $table) {
            $table->string('codice',20)->primary()->comment('Codice Univoco Articolo');
            $table->string('descrizion', 50)->nullable()->comment('Descrizione Articolo');
            $table->string('unmisura',2)->nullable()->comment('Unità di Misura Principale');
            $table->string('unmisura1',2)->nullable()->comment('Unità di Mis. Alt. 1 (==unmisura)');
            $table->string('unmisura2',2)->nullable()->comment('Unità di Mis. Alt. 2');
            $table->string('unmisura3',2)->nullable()->comment('Unità di Mis. Alt. 3');
            $table->double('fatt1')->nullable()->comment('Fattore di Conversione UM1');
            $table->double('fatt2')->nullable()->comment('Fattore di Conversione UM2');
            $table->double('fatt3')->nullable()->comment('Fattore di Conversione UM3');
            $table->string('statoart',1)->nullable()->comment('Stato del Prodotto');
            $table->boolean('lotti')->nullable()->comment('Gestione Lotto?');
            $table->boolean('danger')->nullable()->comment('Imballo?');
            $table->boolean('u_compl')->nullable()->comment('Completo?');
            $table->boolean('u_artlis')->nullable()->comment('Articolo a Listino');
            $table->string('gruppo',5)->nullable()->comment('Gruppo Prodotto');
            $table->string('classe',5)->nullable()->comment('Classe del Prodotto');
            $table->double('qtaconf')->nullable()->comment('Qta Minima di Vendita');
            $table->integer('u_lminv')->nullable()->comment('Lotto Minimo di Vendita');
            $table->integer('u_mminv')->nullable()->comment('Multiplo di Vendita');
            $table->double('listino1')->nullable()->comment('Listino di Vendita 1');
            $table->double('listino6')->nullable()->comment('Listino di Vendita 6');
            $table->double('listino7')->nullable()->comment('Listino di Vendita 7');
            $table->double('prminum')->nullable()->comment('Prz. Minimo di Vendita su UM');
            $table->double('prminum1')->nullable()->comment('Prz. Minimo di Vendita su UM1');
            $table->double('prminum2')->nullable()->comment('Prz. Minimo di Vendita su UM2');
            $table->double('prminum3')->nullable()->comment('Prz. Minimo di Vendita su UM3');
            $table->string('u_maggfm')->nullable()->comment('% Maggiorazione Fuori Misura');
            $table->string('fornstd',6)->nullable()->comment('Fornitore Standard');
            $table->double('scortamin')->nullable()->comment('Scorta Minima');
            $table->double('lottomin')->nullable()->comment('Lotto Minimo');
            $table->double('lottorior')->nullable()->comment('Multipli lotto di Riordino');
            $table->integer('ggrior')->nullable()->comment('Giorni di Riordino');
            $table->double('costoforst')->nullable()->comment('Costo D.B. (=costostand se Acquisto)');
            $table->double('costostand')->nullable()->comment('Costo Standard');
            $table->double('pesounit')->nullable()->comment('Peso Unitario');
            $table->integer('u_misural')->nullable()->comment('Lunghezza in mm');
            $table->integer('u_misurah')->nullable()->comment('Altezza in mm');
            $table->integer('u_misuras')->nullable()->comment('Spessore in mm');
            $table->string('codnomcomb',8)->nullable()->comment('Codice Doganale');
            $table->boolean('nascosto')->nullable()->comment('Nascondi in Browser');
            $table->boolean('u_ce')->nullable()->comment('Certificazione CE');
            $table->boolean('u_ulc')->nullable()->comment('Certificazione ULC');
            $table->boolean('u_fsc')->nullable()->comment('Certificazione FSC');
            $table->boolean('u_pefc')->nullable()->comment('Certificazione PEFC');
            $table->boolean('u_certamb')->nullable()->comment('Certificazione Ambientale');
            $table->string('u_artcorr')->nullable()->comment('Articolo Correlato');
            $table->date('u_datacrea')->nullable()->comment('Data Creazione');
            $table->text('note')->nullable()->comment('Note Varie');
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
        Schema::drop('magart');
    }
}
