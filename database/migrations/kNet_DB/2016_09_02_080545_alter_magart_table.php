<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterMagartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('magart', function (Blueprint $table) {
            $table->boolean('u_perscli')->nullable()->after('u_artlis')->comment('Articolo Personalizzato Cliente');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('magart', function (Blueprint $table) {
            $table->dropColumn(['u_perscli']);
        });
    }
}
