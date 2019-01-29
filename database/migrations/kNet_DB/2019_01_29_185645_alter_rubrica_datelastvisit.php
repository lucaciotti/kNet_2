<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRubricaDatelastvisit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('w_rubrica', function (Blueprint $table) {
            $table->date('date_lastvisit')->nullable()->after('date_nextvisit')->comment('Data Ultima Visita Programmata');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table->dropColumn('date_lastvisit');
    }
}
