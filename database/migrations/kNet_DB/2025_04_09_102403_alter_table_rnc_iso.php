<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRncIso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isornc', function (Blueprint $table) {
            $table->renameColumn('dataini', 'datainit');            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('isornc', function (Blueprint $table) {
            $table->renameColumn('datainit', 'dataini');
        });
    }
}
