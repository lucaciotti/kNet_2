<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAuditDomande extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AuditDomande', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('codice_modello');
            $table->string('super_capitolo');
            $table->string('capitolo');
            $table->string('sub_capitolo');
            $table->string('domanda');
            $table->text('descrizione');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AuditDomande');
    }
}
