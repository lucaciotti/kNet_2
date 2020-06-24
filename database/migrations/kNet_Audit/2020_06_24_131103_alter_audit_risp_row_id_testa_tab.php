<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuditRispRowIdTestaTab extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('AuditRisposteRighe', function (Blueprint $table) {
            $table->integer('tablet_idtesta');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('AuditRisposteRighe', function (Blueprint $table) {
            $table->dropColumn('tablet_idtesta');
        });
    }
}
