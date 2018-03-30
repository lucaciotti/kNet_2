<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagmovTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magmov', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('');
            $table->bigInteger('id_docrig')->unsigned()->comment('');
            $table->date('datamov')->nullable()->comment('');
            $table->string('codicearti',20)->nullable()->comment('');
            $table->string('descrizion',30)->nullable()->comment('');
            $table->string('magazzino',5)->nullable()->comment('');
            $table->string('codcausale',2)->nullable()->comment('');
            $table->double('quantita')->nullable()->comment('');
            $table->string('destprov',1)->nullable()->comment('');
            $table->string('tiporiga',1)->nullable()->comment('');
            $table->tinyInteger('qtacar')->nullable()->comment('');
            $table->tinyInteger('qtascar')->nullable()->comment('');
            $table->tinyInteger('qtaret')->nullable()->comment('');
            $table->string('lotto',20)->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('magmov');
    }
}
