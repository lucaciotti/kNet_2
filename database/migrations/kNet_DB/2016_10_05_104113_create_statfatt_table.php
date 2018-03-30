<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatfattTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_statfatt', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codicecf',6);
            $table->string('agente',3);
            $table->string('agentecli',3);
            $table->string('agentebdg',3);
            $table->string('capoarea',3);
            $table->string('gruppo',6);
            $table->string('prodotto',10);
            $table->integer('mese');
            $table->integer('esercizio');
            $table->string('tipologia',10);
            $table->double('fattmese');
            $table->double('targetmese');
            $table->double('valore');
            $table->double('valore1');
            $table->double('valore2');
            $table->double('valore3');
            $table->double('valore4');
            $table->double('valore5');
            $table->double('valore6');
            $table->double('valore7');
            $table->double('valore8');
            $table->double('valore9');
            $table->double('valore10');
            $table->double('valore11');
            $table->double('valore12');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('u_statfatt');
    }
}
