<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWebVisiteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('w_visite', function (Blueprint $table) {
            $table->bigInteger('rubri_id')->nullable()->after('codicecf')->comment('Contatto rubrica della visita');
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
            $table->dropColumn('rubri_id');
        });
    }
}
