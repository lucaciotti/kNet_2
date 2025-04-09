<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRncDoc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('u_rncdoc', function (Blueprint $table) {
            $table->renameColumn('idrnc', 'id_rnc');
            $table->renameColumn('iddoc', 'id_doc');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('u_rncdoc', function (Blueprint $table) {
            $table->renameColumn('id_rnc', 'idrnc');
            $table->renameColumn('id_doc', 'iddoc');
        });
    }
}
