<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagaliasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magalias', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('');
            $table->tinyInteger('idprog')->nullable()->comment('');
            $table->string('codicearti',20)->nullable()->comment('');
            $table->string('alias',20)->nullable()->comment('');
            $table->string('unmisura',2)->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('magalias');
    }
}
