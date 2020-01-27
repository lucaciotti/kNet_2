<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateStatABCTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_statabc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('codicecf',6);
            $table->string('codag',3);
            $table->string('esercizio', 4);
            $table->integer('mese');
            $table->string('articolo',20);
            $table->string('gruppo',6);
            $table->string('prodotto',10);
            $table->boolean('isomaggio');
            $table->double('qta');
            $table->double('qta1');
            $table->double('qta2');
            $table->double('qta3');
            $table->double('qta4');
            $table->double('qta5');
            $table->double('qta6');
            $table->double('qta7');
            $table->double('qta8');
            $table->double('qta9');
            $table->double('qta10');
            $table->double('qta11');
            $table->double('qta12');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('u_statabc');
    }
}
