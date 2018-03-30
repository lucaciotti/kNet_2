<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDoctesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('doctes', function (Blueprint $table) {
          $table->double('spesetr')->nullable()->comment('Spese Trasporto a Carico del Cliente');
          $table->string('spesetriva',3)->nullable()->comment('Iva Su Spese Trasporto');
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
            $table->dropColumn(['spesetr', 'spesetriva']);
        });
    }
}
