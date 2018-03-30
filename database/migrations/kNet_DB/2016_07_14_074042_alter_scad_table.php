<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterScadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->bigInteger('iddist')->unsigned()->nullable()->comment('ID Dstinta per raggruppare effetti emessi');
            $table->bigInteger('id_storia')->unsigned()->nullable()->comment('ID Collegamente a Crediti_st DISUSO');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->dropColumn(['iddist', 'id_storia']);
        });
    }
}
