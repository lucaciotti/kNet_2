<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRubricaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('w_rubrica', function (Blueprint $table) {
            $table->boolean('isModCarp01')->default(false)->after('nDipendenti')->comment('Modulo Fatto');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('w_rubrica', function (Blueprint $table) {
            $table->dropColumn('isModCarp01');
        });
    }
}
