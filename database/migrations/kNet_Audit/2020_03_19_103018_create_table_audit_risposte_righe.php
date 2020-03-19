<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableAuditRisposteRighe extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AuditRisposteRighe', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->integer('id_testa')->unsigned();
            $table->integer('id_domanda')->unsigned();
            $table->boolean('risposta')->default(false);
            $table->text('osservazioni');
            $table->text('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('AuditRisposteRighe');
    }
}
