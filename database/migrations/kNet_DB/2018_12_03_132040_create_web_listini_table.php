<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebListiniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_listok', function (Blueprint $table) {
            $table->increments('id')->comment('ID univoco per ogni visita');
            $table->bigInteger('listini_id')->comment('ID Listino da Estendere');
            $table->string('esercizio')->comment('Esercizio di Estenzione');
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
        Schema::dropIfExists('w_listok');
    }
}
