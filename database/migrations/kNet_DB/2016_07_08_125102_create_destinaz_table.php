<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinazTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinaz', function (Blueprint $table) {
            $table->string('codicecf', 6)->comment('Codice Cli For');
            $table->string('codicedes', 4)->comment('Cod Destinazione');
            $table->string('ragionesoc', 30)->nullable()->comment('Ragione Sociale');
            $table->string('suppragsoc', 30)->nullable()->comment('Supplemento Ragione Sociale');
            $table->string('indirizzo', 50)->nullable()->comment('Indirizzo');
            $table->string('cap', 5)->nullable()->comment('CAP');
            $table->string('localita', 30)->nullable()->comment('Località');
            $table->string('provincia', 2)->nullable()->comment('Provincia');
            $table->string('telefono', 16)->nullable()->comment('Telefono');
            $table->string('fax', 16)->nullable()->comment('Fax');
            $table->string('personarif', 30)->nullable()->comment('Persona Riferimento');
            $table->string('u_nazione', 3)->nullable()->comment('Nazione');
            $table->string('u_ind2', 40)->nullable()->comment('Indirizzo 2');
            $table->primary(['codicecf', 'codicedes']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('destinaz');
    }
}
