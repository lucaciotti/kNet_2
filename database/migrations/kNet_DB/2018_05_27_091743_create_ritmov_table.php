<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRitmovTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rit_mov', function (Blueprint $table) {
            $table->increments('id')->comment('');
            $table->string('codfor',6)->nullable()->comment('');
            $table->date('ftdatadoc')->nullable()->comment('');
            $table->string('ftnumdoc', 10)->nullable()->comment('');
            $table->double('totfattura')->nullable()->comment('');
            $table->double('compensi')->nullable()->comment('');
            $table->double('perendit')->nullable()->comment('');
            $table->double('impendit')->nullable()->comment('');
            $table->double('perenage')->nullable()->comment('');
            $table->double('impenage')->nullable()->comment('');
            $table->timestamp('timestamp')->nullable()->comment('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rit_mov');
    }
}
