<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNazioniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nazioni', function (Blueprint $table) {
          $table->string('codice', 3)->primary()->comment('Codice Univoco');
          $table->string('descrizion', 50)->nullable()->comment('');
          $table->string('codiceiso', 2)->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('nazioni');
    }
}
