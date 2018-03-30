<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMagoiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('magoi', function (Blueprint $table) {
            $table->string('articolo', 20);
            $table->string('magazzino',5);
            $table->double('ordinato')->nullable()->comment('');
            $table->double('impegnato')->nullable()->comment('');
            $table->primary(['articolo', 'magazzino']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('magoi');
    }
}
