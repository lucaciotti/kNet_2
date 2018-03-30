<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaggiaclTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maggiacl', function (Blueprint $table) {
            $table->string('articolo', 20);
            $table->string('magazzino',5);
            $table->string('lotto', 20);
            $table->double('progqtacar')->nullable()->comment('');
            $table->double('progqtasca')->nullable()->comment('');
            $table->double('progqtaret')->nullable()->comment('');
            $table->primary(['articolo', 'magazzino', 'lotto']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('maggiacl');
    }
}
