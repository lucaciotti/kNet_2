<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableBudgana extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('u_budgana', function (Blueprint $table) {
            $table->decimal('u_budg1',15,2)->nullable()->default(0.0)->change();
            $table->decimal('u_budg2', 15, 2)->nullable()->default(0.0)->change();
            $table->decimal('u_budg3', 15, 2)->nullable()->default(0.0)->change();
            $table->decimal('u_kobudg1', 15, 2)->nullable()->default(0.0)->change();
            $table->decimal('u_kobudg2', 15, 2)->nullable()->default(0.0)->change();
            $table->decimal('u_kobudg3', 15, 2)->nullable()->default(0.0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('u_budgana', function (Blueprint $table) {
            $table->float('u_budg1')->nullable()->default(0.0)->change();
            $table->float('u_budg2')->nullable()->default(0.0)->change();
            $table->float('u_budg3')->nullable()->default(0.0)->change();
            $table->float('u_kobudg1')->nullable()->default(0.0)->change();
            $table->float('u_kobudg2')->nullable()->default(0.0)->change();
            $table->float('u_kobudg3')->nullable()->default(0.0)->change();
        });
    }
}
