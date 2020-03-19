<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditDomandeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('AuditDomande')->insert([
            'id' => 1,
            'descrizione' => "Audit Preliminare",
            'versione' => "1.5.3",
        ]);
    }
}
