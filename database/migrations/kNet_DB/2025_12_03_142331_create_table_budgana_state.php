<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBudganaState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_budgana', function (Blueprint $table) {
            $table->string('codice', 6)->nullable();
            $table->float('u_budg1')->nullable()->default(0.0);
            $table->string('u_budg1p', 8)->nullable()->default('');
            $table->float('u_budg2')->nullable()->default(0.0);
            $table->string('u_budg2p', 8)->nullable()->default('');
            $table->float('u_budg3')->nullable()->default(0.0);
            $table->string('u_budg3p', 8)->nullable()->default('');
            $table->float('u_kobudg1')->nullable()->default(0.0);
            $table->string('u_kobudg1p', 8)->nullable()->default('');
            $table->float('u_kobudg2')->nullable()->default(0.0);
            $table->string('u_kobudg2p', 8)->nullable()->default('');
            $table->float('u_kobudg3')->nullable()->default(0.0);
            $table->string('u_kobudg3p', 8)->nullable()->default('');
            $table->boolean('propcontra')->nullable()->default(false);
            $table->string('esercizio', 4)->nullable()->default('');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_budgana');
    }
}
