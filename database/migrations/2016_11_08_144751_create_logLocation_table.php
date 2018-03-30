<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_location', function (Blueprint $table) {
            $table->increments('id')->comment('Unique Id');
            $table->string('ip_address',45)->comment('Remote Ip');
            $table->integer('user_id')->unsigned()->nullable()->comment('Id dell\'utente');
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
        Schema::drop('log_location');
    }
}
