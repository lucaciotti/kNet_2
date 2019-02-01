<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('w_visite', function (Blueprint $table) {
            $table->bigInteger('modCarp_id')->nullable()->after('note')->comment('Riferimento a Analisi di Mercato 2019');
            $table->string('codicecf',6)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('w_visite', function (Blueprint $table) {
            $table->dropColumn('modCarp_id');
            $table->string('codicecf',6)->nullable(false)->change();
        });
    }
}
