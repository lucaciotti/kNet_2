<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNumerodocDoctes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctes', function (Blueprint $table) {
            $table->string('numerodoc', 8)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctes', function (Blueprint $table) {
            $table->string('numerodoc', 2)->change();
        });
    }
}
