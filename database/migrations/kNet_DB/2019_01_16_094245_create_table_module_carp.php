<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableModuleCarp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_mod_carp_01', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('rubri_id')->unsigned()->comment('');
            $table->boolean('prod_mobili')->default(false)->comment('');
            $table->boolean('prod_porte')->default(false)->comment('');
            $table->boolean('prod_portefinestre')->default(false)->comment('');
            $table->boolean('prod_cucine')->default(false)->comment('');
            $table->boolean('prod_other')->default(false)->comment('');
            $table->boolean('prod_isMulti')->default(false)->comment('');
            $table->text('prod_note')->nullable()->comment('');
            $table->boolean('know_kk')->default(false)->comment('');
            $table->boolean('isKkBuyer')->default(false)->comment('');
            $table->integer('yes_supplierType')->nullable()->comment('');
            $table->string('yes_supplierName', 100)->nullable()->comment('');
            $table->boolean('yes_isInformato')->nullable()->comment('');
            $table->boolean('not_why_prezzo')->nullable()->comment('');
            $table->boolean('not_why_qualita')->nullable()->comment('');
            $table->boolean('not_why_servizio')->nullable()->comment('');
            $table->boolean('not_why_catalogo')->nullable()->comment('');
            $table->boolean('not_why_noinfo')->nullable()->comment('');
            $table->integer('not_supplierType')->nullable()->comment('');
            $table->string('not_supplierName', 100)->nullable()->comment('');
            $table->boolean('wants_tryKK')->nullable()->comment('');
            $table->text('notryKK_note')->nullable()->comment('');
            $table->boolean('wants_info')->nullable()->comment('');
            $table->text('final_note')->nullable()->comment('');
            $table->integer('vote')->default(0)->comment('');
            $table->bigInteger('user_id')->unsigned()->comment('');
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
        Schema::dropIfExists('w_mod_carp_01');
    }
}
