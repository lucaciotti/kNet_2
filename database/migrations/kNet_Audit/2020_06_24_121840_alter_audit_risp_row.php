<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuditRispRow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('AuditRisposteRighe', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('AuditRisposteRighe', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('tablet_id')->change();
            $table->integer('voto');
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
            $table->dropPrimary();
            $table->string('tablet_id')->change();
            $table->dropColumn('voto');
        });
    }
}
