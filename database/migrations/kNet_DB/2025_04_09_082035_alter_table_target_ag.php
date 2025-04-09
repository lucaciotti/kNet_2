<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\Type;

class AlterTableTargetAg extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('double')) {
            Type::addType('double', FloatType::class);
        }

        Schema::table('u_targetag', function (Blueprint $table) {
            $table->dropColumn(['targetkr', 'targetko']);
        });
        Schema::table('u_targetag', function (Blueprint $table) {
            $table->string('esercizio', 4)->nullable()->change();
            $table->double('targetkr', 12, 2)->nullable()->default(0.00)->after('esercizio');
            $table->double('targetko', 12, 2)->nullable()->default(0.00)->after('esercizio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('u_targetag', function (Blueprint $table) {
            $table->dropColumn(['targetkr', 'targetko']);
        });
        Schema::table('u_targetag', function (Blueprint $table) {
            $table->integer('esercizio')->unsigned()->nullable()->default(0)->change();
            $table->float('targetkr', 8, 2)->nullable()->default(0.00)->after('esercizio');
            $table->float('targetko', 8, 2)->nullable()->default(0.00)->after('esercizio');
        });
    }
}
