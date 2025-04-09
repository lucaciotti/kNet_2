<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableNazioni extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('nazioni', function (Blueprint $table) {
            $table->dropPrimary();
            $table->unique(['codice', 'codiceiso']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nazioni', function (Blueprint $table) {
            $table->dropPrimary();
            $table->unique(['codice']);
        });
    }
}
