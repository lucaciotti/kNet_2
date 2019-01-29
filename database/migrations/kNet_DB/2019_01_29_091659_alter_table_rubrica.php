<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableRubrica extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('w_rubrica', function (Blueprint $table) {
            $table->string('descrizion', 100)->change();
            $table->integer('nDipendenti')->nullable()->after('agente')->comment('Numero di Dipendenti');
            $table->string('codicecf',6)->nullable()->after('agente')->comment('Codice Cliente Associato ARCA');
            $table->boolean('prod_mobili')->default(false)->after('agente')->comment('Produttore Mobili');
            $table->boolean('prod_porte')->default(false)->after('agente')->comment('Produttore Porte');
            $table->boolean('prod_portefinestre')->default(false)->after('agente')->comment('Produttore Finestre');
            $table->boolean('prod_cucine')->default(false)->after('agente')->comment('Produttore Cucine');
            $table->boolean('prod_other')->default(false)->after('agente')->comment('Produttore Altro');
            $table->text('prod_note')->nullable()->after('agente')->comment('Specifica Altro');
            $table->boolean('wants_info')->nullable()->after('agente')->comment('Vuole Info su KK');
            $table->boolean('wants_tryKK')->nullable()->after('agente')->comment('Vuole Provare KK');
            $table->boolean('know_kk')->nullable()->after('agente')->comment('Conosce già KK');
            $table->boolean('isKkBuyer')->nullable()->after('agente')->comment('Acquista Prodotti KK');
            $table->integer('vote')->default(0)->after('agente')->comment('Valutazione');
            $table->string('code_ateco',10)->nullable()->after('agente')->comment('Codice Ateco');
            $table->date('date_nextvisit')->nullable()->after('agente')->comment('Data Prossima Visita Programmata');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('w_rubrica', function (Blueprint $table) {
            $table->dropColumn(['nDipendenti', 'codicecf', 'prod_mobili', 'prod_porte',
            'prod_portefinestre', 'prod_cucine', 'prod_other', 'prod_note', 'wants_info',
            'wants_tryKK', 'know_kk', 'isKkBuyer', 'vote', 'code_ateco', 'date_nextvisit']);
            $table->string('descrizion', 50)->change();
        });
    }
}
