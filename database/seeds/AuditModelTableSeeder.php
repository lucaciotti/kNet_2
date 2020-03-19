<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AuditModelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('AuditModels')->insert([
            'codice' => "001",
            'descrizione' => "Audit Fornitore",
            'versione' => "Rev. 03 del 06-04-2012",
        ]);

        DB::table('AuditModels')->insert([
            'codice' => "002",
            'descrizione' => "Audit Preliminare",
            'versione' => "1.5.3",
        ]);
    }
}
