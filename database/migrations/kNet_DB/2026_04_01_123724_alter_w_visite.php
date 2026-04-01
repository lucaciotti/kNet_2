<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterWVisite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('w_visite', function (Blueprint $table) {
            $table->boolean('lastOfDay')->nullable()->default(false);
            $table->boolean('lastOfDayType')->nullable()->default(false);
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
            $table->dropColumn('lastOfDay');
            $table->dropColumn('lastOfDayType');
        });
    }
}
