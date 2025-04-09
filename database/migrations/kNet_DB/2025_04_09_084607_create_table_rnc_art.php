<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRncArt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_rncart', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('ID Univoco');
            $table->string('codicearti', 20)->nullable()->comment('Codice Articolo Movimentato');
            $table->string('descrizion', 50)->nullable()->comment('Descrizione della Riga o Articolo');
            $table->string('unmisura', 2)->nullable()->comment('Unita di Misura utilizzata');
            $table->double('quantita')->nullable()->comment('Quantità movimentata');
            $table->string('lotto', 20)->nullable()->comment('Codice Lotto Articolo Movimentato');
            $table->string('statoart', 1)->nullable()->comment('Stato difettosità -> Join: U_RNCARTST');
            $table->bigInteger('idrigadoc')->unsigned()->comment('ID Docrig');
            $table->bigInteger('idtestadoc')->unsigned()->comment('ID Doctes');
            $table->bigInteger('idrnc')->unsigned()->comment('ID Rnciso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('u_rncart');
    }
}
