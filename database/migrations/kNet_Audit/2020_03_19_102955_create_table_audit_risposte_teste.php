<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAuditRisposteTeste extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AuditRisposteTeste', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('codice_modello');
            $table->string('azienda');
            $table->dateTime('data');
            $table->string('auditor');
            $table->string('persone_intervistate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AuditRisposteTeste');
    }
}
