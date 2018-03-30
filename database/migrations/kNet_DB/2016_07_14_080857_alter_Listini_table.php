<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterListiniTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('listini', function (Blueprint $table) {
            $table->renameColumn('listino', 'u_listino');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('listini', function (Blueprint $table) {
            $table->renameColumn('u_listino', 'listino');
        });
    }
}
