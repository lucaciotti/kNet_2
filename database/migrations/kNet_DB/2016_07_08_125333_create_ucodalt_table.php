<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUcodaltTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_codalt', function (Blueprint $table) {
          $table->string('codicearti', 20);
          $table->string('codclifor', 6);
          $table->string('codartfor', 20);
          $table->string('codicedes', 4);
          $table->string('lingua', 2)->nullable()->comment('');
          $table->string('logo', 254)->nullable()->comment('');
          $table->string('barcode', 20)->nullable()->comment('');
          $table->primary(['codicearti', 'codartfor', 'codclifor', 'codicedes']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('u_codalt');
    }
}
