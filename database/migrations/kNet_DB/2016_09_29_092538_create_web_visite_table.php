<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWebVisiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_visite', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('ID univoco per ogni visita');
            $table->string('codicecf',6)->comment('Codice Cliente / Fornitore');
            $table->bigInteger('user_id')->comment('Utente della visita');
            $table->date('data')->comment('Data della Visita');
            $table->string('tipo')->comment('Tipo di Visita');
            $table->string('descrizione')->comment('Descrizione Visita');
            $table->text('note')->nullable()->comment('Memo della Visita');
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
        Schema::drop('w_visite');
    }
}
