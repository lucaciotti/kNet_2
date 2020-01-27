<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTrackingDDT extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctes', function (Blueprint $table) {
            $table->String('patrasf')->nullable()->comment('Codice Tracking DDT');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('doctes', function (Blueprint $table) {
            $table->dropColumn('patrasf');
        });
    }
}
