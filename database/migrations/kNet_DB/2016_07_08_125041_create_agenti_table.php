<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenti', function (Blueprint $table) {
            $table->string('codice', 3)->primary()->comment('Codice Univoco');
            $table->string('descrizion', 30)->nullable()->comment('Nome, Ragione Sociale');
            $table->string('provv',10)->nullable()->comment('Provvigione');
            $table->string('fornitore', 6)->nullable()->comment('Fornitore Collegato');
            $table->timestamp('timestamp')->nullable()->comment('');
            $table->string('email',50)->nullable()->comment('Email');
            $table->string('u_capoa',3)->nullable()->comment('Capo area Agente');
            $table->string('u_commessa',6)->nullable()->comment('Commessa Collegata');
            $table->string('u_codcli',6)->nullable()->comment('Codice Cliente Collegato');
            $table->date('u_dataini')->nullable()->comment('Data Inizio Rapporto');
            $table->date('u_datafine')->nullable()->comment('Data Fine Rapporto');
            $table->double('u_budg1')->nullable()->comment('Bdg Krona');
            $table->double('u_kobudg1')->nullable()->comment('Bdg Koblenz');
            $table->double('u_kotarget')->nullable()->comment('Indirizzo');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('agenti');
    }
}
