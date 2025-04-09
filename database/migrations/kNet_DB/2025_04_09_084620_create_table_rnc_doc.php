<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRncDoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_rncdoc', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('ID univoco');
            $table->bigInteger('idrnc')->unsigned()->comment('ID Rnciso');
            $table->bigInteger('iddoc')->unsigned()->comment('ID Doctes');
            $table->string('tipodoc', 2)->nullable()->comment('Codice Documento');
            $table->string('numerodoc', 2)->nullable()->comment('Numero Progressivo per Tipo Documento');
            $table->date('datadoc')->nullable()->comment('Data Emissione Documento');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('u_rncdoc');
    }
}
