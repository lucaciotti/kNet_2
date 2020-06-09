<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRitEnasarco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rit_enasarco', function (Blueprint $table) {            
            $table->string('fornitore',6)->nullable()->comment('');
            $table->string('anno', 4)->nullable()->comment('');
            $table->double('percage')->nullable()->comment('');
            $table->double('percditta')->nullable()->comment('');
            $table->double('minimale')->nullable()->comment('');
            $table->double('massimale')->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rit_enasarco');
    }
}
