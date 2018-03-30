<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
          $table->string('codag',3)->nullable()->comment('Codice Agente Associato');
          $table->string('codcli',6)->nullable()->comment('Codice Cliente Associato');
          $table->string('codfor',6)->nullable()->comment('Codice Fornitore Associato');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['codag', 'codcli', 'codfor']);
        });
    }
}
