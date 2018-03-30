<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagclsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magcls', function (Blueprint $table) {
            $table->string('codice', 5)->primary()->comment('Codice Univoco');
            $table->string('descrizion', 30)->nullable()->comment('');
            $table->tinyInteger('livello')->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('magcls');
    }
}
