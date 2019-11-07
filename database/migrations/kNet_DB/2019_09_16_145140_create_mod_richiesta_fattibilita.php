<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModRichiestaFattibilita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('w_modricfat', function (Blueprint $table) {
            $table->increments('id');
            $table->date('data_ricezione')->comment('');
            $table->string('richiedente')->comment('');
            $table->string('email_richiedente')->comment('');
            $table->string('ragione_sociale')->comment('');
            $table->string('codicecf', 6)->comment('');
            $table->string('tipologia_prodotto')->comment('');
            $table->text('descr_pers')->comment('');
            $table->string('url_pers')->nullable()->comment('');
            $table->string('system_kk')->nullable()->comment('');
            $table->string('system_other')->nullable()->comment('');
            $table->text('info_tecn_comm')->comment('');
            $table->text('imballaggio')->nullable()->comment('');
            $table->string('um')->comment('');
            $table->integer('quantity')->unsigned()->comment('');
            $table->string('periodo_ordinativi')->comment('');
            $table->double('target_price')->comment('');
            $table->string('ditta')->comment('');
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
        Schema::dropIfExists('Modricfat');
    }
}
