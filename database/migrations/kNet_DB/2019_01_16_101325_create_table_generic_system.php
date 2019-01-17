<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGenericSystem extends Migration
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
            $table->integer('livello')->comment('Livello di ');
            $table->string('descrizione', 100)->comment('Codice Univoco');
            $table->string('url', 255)->comment('Codice Univoco');
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
        Schema::dropIfExists('w_generic_system');
    }
}
