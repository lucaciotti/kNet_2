<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSystemMkt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_system_mkt', function (Blueprint $table) {
            $table->string('codice', 6)->primary()->comment('Codice Univoco');
            $table->integer('livello')->comment('Livello di dettaglio: 0 -> Krona,Koblenz... ; 1 -> System Generico ; 2 -> System specifico ');
            $table->string('descrizione', 100)->comment('Descrizione System');
            $table->string('url', 255)->comment('Link Sito Mkt');
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
        Schema::dropIfExists('w_system_mkt');
    }
}
