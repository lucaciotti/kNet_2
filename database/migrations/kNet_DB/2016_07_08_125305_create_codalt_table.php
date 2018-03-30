<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCodaltTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codalt', function (Blueprint $table) {
            $table->string('codicearti', 20);
            $table->string('codclifor', 6);
            $table->string('codartfor', 20);
            $table->string('u_coddis', 20)->nullable()->comment('');
            $table->string('u_barcode', 20)->nullable()->comment('');
            $table->primary(['codicearti', 'codartfor', 'codclifor']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('codalt');
    }
}
