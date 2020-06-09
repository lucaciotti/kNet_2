<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuditRispTesIdTablet extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('AuditRisposteTeste', function (Blueprint $table) {
            $table->string('tablet_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('AuditRisposteTeste', function (Blueprint $table) {
            $table->dropColumn('tablet_id');            //
        });
    }
}
