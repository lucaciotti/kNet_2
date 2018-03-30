<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditiStTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('crediti_st', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('ID univoco');
            $table->date('datareg')->nullable()->comment('Data Registrazione');
            $table->string('cliente',6)->nullable()->comment('Cliente');
            $table->text('note')->nullable()->comment('Dialogo col Cliente');
            $table->boolean('avvisa')->nullable()->comment('Avvisare');
            $table->date('dataavv')->nullable()->comment('Data di Avviso');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('crediti_st');
    }
}
