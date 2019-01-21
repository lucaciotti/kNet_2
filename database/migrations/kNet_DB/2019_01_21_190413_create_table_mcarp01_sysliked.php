<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMcarp01Sysliked extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_mcarp01_sysLiked', function (Blueprint $table) {
            $table->increments('id');
            // $table->integer('type')->unsigned()->comment('Tipologia: 1->sysKnown; 2->sysBuyOfKK; 3->sysBuyOfOther;4->sysLiked');
            $table->bigInteger('mcarp01_id')->unsigned()->comment('');
            $table->string('sysmkt_cod',6)->comment('');
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
        Schema::dropIfExists('w_mcarp01_sysLiked');
    }
}
