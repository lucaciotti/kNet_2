<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRncIso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('isornc', function (Blueprint $table) {
            $table->bigInteger('id')->unsigned()->primary()->comment('ID Univoco');
            $table->integer('nummov')->unsigned()->nullable();
            $table->string('codfor', 6)->nullable();
            $table->date('datareg')->nullable();
            $table->date('dataini')->nullable();
            $table->date('dataend')->nullable();
            $table->string('descrizion', 40)->nullable();
            $table->float('oreperse')->nullable();
            $table->float('costo')->nullable();
            $table->float('costoman')->nullable()->comment('Costo Manodopera');
            $table->string('doceserc', 4)->nullable();
            $table->string('doctip', 2)->nullable();
            $table->string('docnmov', 8)->nullable();
            $table->string('ctiporap', 4)->nullable()->comment('Join: isornt');
            $table->string('causa', 4)->nullable()->comment('Join: isocause');
            $table->text('dettaglio')->nullable();
            $table->text('azione')->nullable();
            $table->text('verifyapp')->nullable()->comment('Verifica appl');
            $table->date('vappdate')->nullable();
            $table->text('valeff')->nullable()->comment('Verifica efficacia');
            $table->date('veffdate')->nullable();
            $table->integer('difett')->unsigned()->nullable()->comment('Gravità difetto');
            $table->string('u_vettore', 4)->nullable();
            $table->string('u_dipa', 13)->nullable()->comment('Dipendente Apre - Join: Dipendenti');
            $table->string('u_dip1', 13)->nullable()->comment('Dipendente Analisi');
            $table->string('u_dip2', 13)->nullable()->comment('Dipendente Attività Correttiva');
            $table->string('u_dipc', 13)->nullable()->comment('Dipendente Chiude');
            $table->boolean('u_pub')->nullable()->default(false);
            $table->string('u_tc', 1)->nullable()->comment('Trasporto a carico di: C->Cliente, F->Fornitore, K->KK, V->Vettore');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('isornc');

    }
}
