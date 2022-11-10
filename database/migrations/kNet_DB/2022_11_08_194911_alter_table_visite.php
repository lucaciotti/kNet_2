<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableVisite extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('w_visite', function (Blueprint $table) {
            $table->string('persona_contatto')->after('data')->default('')->comment('Persona contattata');
            $table->string('funzione_contatto')->after('persona_contatto')->default('')->comment('Funzione Aziedale della persona contattata');
            $table->text('conclusione')->after('note')->default('')->comment('Conclusioni personali dell\'incontro');
            $table->boolean('ordine')->after('conclusione')->default(false)->comment('Seguirà ordine cliente?');
            $table->string('data_prox')->after('ordine')->nullable()->comment('Data prossima visita');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('w_visite', function (Blueprint $table) {
            $table->dropColumn(['persona_contatto', 'funzione_contatto', 'conclusione', 'ordine', 'data_prox']);
        });
    }
}
