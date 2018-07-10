<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateScadenzeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->double('impprovlit')->nullable()->comment('Imp. Provvigioni in €');
            $table->double('impprovliq')->nullable()->comment('Imp. Provvigioni liquidate');
            $table->boolean('liquidate')->nullable()->comment('isLiquidata?');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('scadenze', function (Blueprint $table) {
            $table->dropColumn(['impprovlit', 'impprovliq', 'liquidate']);
        });
    }
}
