<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableTargetAg2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('u_targetag', function (Blueprint $table) {
            $table->float('premio_valuta')->nullable()->default(0.0);
            $table->string('premio_perc', 255)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('u_targetag', function (Blueprint $table) {
            $table->dropColumn(['premio_valuta', 'premio_perc']);
        });
    }
}
