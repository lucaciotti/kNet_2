<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRitanaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rit_ana', function (Blueprint $table) {
            $table->string('codfor', 6)->primary();
            $table->string('sesso', 1)->nullable();
            $table->date('datanasc', 8)->nullable();
            $table->string('capnasc', 5)->nullable();
            $table->string('comunenasc', 30)->nullable();
            $table->string('provnasc', 2)->nullable();
            $table->string('indres', 35)->nullable();
            $table->string('capres', 5)->nullable();
            $table->string('comuneres', 30)->nullable();
            $table->string('provres', 2)->nullable();
            $table->float('pcdefrit')->nullable();
            $table->double('percimp')->nullable();
            $table->string('codtributo', 4)->nullable();
            $table->integer('tipoage')->nullable();
            $table->double('perendit')->nullable();
            $table->double('perenage')->nullable();
            $table->double('impmin')->nullable();
            $table->double('impmax')->nullable();
            $table->double('impinps')->nullable();
            $table->double('percinps')->nullable();
            $table->double('perc_cp')->nullable();
            $table->double('perc_gs')->nullable();
            $table->double('perc_sp')->nullable();
            $table->string('caupag',2)->nullable();
            $table->string('cognome',40)->nullable();
            $table->string('nome',40)->nullable();
            $table->string('cat_part',2)->nullable();
            $table->string('ev_eccez',1)->nullable();
            $table->string('cf_rapp',16)->nullable();
            $table->timestamp('timestamp')->nullable();
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rit_ana');
    }
}
