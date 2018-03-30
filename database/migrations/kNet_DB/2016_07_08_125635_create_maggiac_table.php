<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaggiacTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maggiac', function (Blueprint $table) {
            $table->string('esercizio', 4);
            $table->string('articolo', 20);
            $table->string('magazzino',5);
            $table->double('giacini')->nullable()->comment('');
            $table->double('progqtacar')->nullable()->comment('');
            $table->double('progqtasca')->nullable()->comment('');
            $table->double('progqtaret')->nullable()->comment('');
            $table->primary(['esercizio', 'articolo', 'magazzino']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('maggiac');
    }
}
