<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAuditRispTes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('AuditRisposteTeste', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('AuditRisposteTeste', function (Blueprint $table) {
            $table->integer('id')->unsigned()->autoIncrement();
            $table->integer('tablet_id')->change();
            $table->text('conclusioni');
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
            $table->dropPrimary();
            $table->string('tablet_id')->change();
            $table->dropColumn('conclusioni');
        });
        
    }
}
