<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTargetAg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_targetag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('esercizio')->unsigned()->nullable()->default(0);
            $table->float('targetkr')->nullable()->default(0.00);
            $table->float('targetko')->nullable()->default(0.00);
            $table->string('codage', 3);
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
        Schema::dropIfExists('u_targetag');
    }
}
