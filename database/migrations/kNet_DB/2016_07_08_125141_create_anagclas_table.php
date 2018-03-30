<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnagclasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('anagclas', function (Blueprint $table) {
          $table->string('codice', 3)->primary()->comment('Codice Univoco');
          $table->string('descrizion', 50)->nullable()->comment('Descrizione');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('anagclas');
    }
}
