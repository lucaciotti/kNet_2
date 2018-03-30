<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaglangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maglang', function (Blueprint $table) {
            $table->string('codicearti', 20);
            $table->string('codlingua',3);
            $table->string('descrizion',40)->nullable()->comment('');
            $table->string('unmisura',2)->nullable()->comment('');
            $table->text('note')->nullable()->comment('');
            $table->primary(['codicearti', 'codlingua']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('maglang');
    }
}
