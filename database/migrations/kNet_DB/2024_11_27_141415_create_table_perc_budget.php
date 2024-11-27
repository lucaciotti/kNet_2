<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTablePercBudget extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_budget_perc', function (Blueprint $table) {
            $table->string('ditta');
            $table->integer('anno')->unsigned()->nullable()->default(0);
            $table->integer('mese')->unsigned()->nullable()->default(0);
            $table->float('perc')->nullable()->default(0.00);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_budget_perc');
    }
}
