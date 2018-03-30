<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistbaseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('distbase', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('');
            $table->string('codpadre',20)->nullable()->comment('');
            $table->integer('numeroriga')->nullable()->comment('');
            $table->string('codcomp',20)->nullable()->comment('');
            $table->string('unmisura',2)->nullable()->comment('');
            $table->double('fatt')->nullable()->comment('');
            $table->double('quantita')->nullable()->comment('');
            $table->double('quantalt')->nullable()->comment('');
            $table->double('coeff1')->nullable()->comment('');
            $table->double('coeff2')->nullable()->comment('');
            $table->double('coeff3')->nullable()->comment('');
            $table->double('coeff4')->nullable()->comment('');
            $table->date('datainival')->nullable()->comment('');
            $table->date('datafinval')->nullable()->comment('');
            $table->string('tipoparte',1)->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('distbase');
    }
}
