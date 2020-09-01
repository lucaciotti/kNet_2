<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableNoteComm extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_anagnote', function (Blueprint $table) {
            $table->increments('id');
            $table->string('codicecf',6);
            $table->string('tipodip')->nullable()->comment('Tipologia Dipendente');
            $table->string('tipomodulo')->nullable()->comment('Tipologia Documento');
            $table->text('note')->nullable()->comment('Note Commerciali');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_anagnote');
    }
}
