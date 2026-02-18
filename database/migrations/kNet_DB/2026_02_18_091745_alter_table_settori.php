<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableSettori extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('settori', function (Blueprint $table) {
            $table->string('descrizion', 100)->nullable()->comment('')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('settori', function (Blueprint $table) {
            $table->string('descrizion', 30)->nullable()->comment('')->change();
        });
    }
}
