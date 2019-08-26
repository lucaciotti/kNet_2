<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUStatfattArt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_statfatt_art', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('esercizio');
            $table->integer('mese_parz');
            $table->string('codicecf', 6);
            $table->string('sector_doc', 3);
            $table->string('agente', 3);
            $table->string('agente_doc', 3);
            $table->string('capoarea', 3);
            $table->string('codicearti', 20);
            $table->string('macrogrp', 3);
            $table->string('gruppo', 6);
            $table->string('prodotto', 10);
            $table->double('qta_tot');
            $table->double('val_tot');
            $table->double('qta_01');
            $table->double('val_01');
            $table->double('qta_02');
            $table->double('val_02');
            $table->double('qta_03');
            $table->double('val_03');
            $table->double('qta_04');
            $table->double('val_04');
            $table->double('qta_05');
            $table->double('val_05');
            $table->double('qta_06');
            $table->double('val_06');
            $table->double('qta_07');
            $table->double('val_07');
            $table->double('qta_08');
            $table->double('val_08');
            $table->double('qta_09');
            $table->double('val_09');
            $table->double('qta_10');
            $table->double('val_10');
            $table->double('qta_11');
            $table->double('val_11');
            $table->double('qta_12');
            $table->double('val_12');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_statfatt_art');
    }
}
