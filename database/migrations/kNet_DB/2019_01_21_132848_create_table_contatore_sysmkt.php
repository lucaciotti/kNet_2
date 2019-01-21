<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableContatoreSysmkt extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_cont_sysmkt', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cod_base',2);
            $table->integer('livello1')->default(0);
            $table->integer('livello2')->default(0);
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
        Schema::dropIfExists('w_cont_sysmkt');
    }
}
