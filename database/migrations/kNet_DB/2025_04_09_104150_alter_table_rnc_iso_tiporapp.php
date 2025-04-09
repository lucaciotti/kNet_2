<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRncIsoTiporapp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('isornc', function (Blueprint $table) {
            $table->renameColumn('ctiporap', 'ctiporapp');
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
            $table->renameColumn('ctiporapp', 'ctiporap');
        });
    }
}
