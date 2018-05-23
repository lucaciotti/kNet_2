<?php

use Illuminate\Database\Migrations\Migration;
//use Artisan;

class UpdateCurrencyTable extends Migration
{
    /**
     * Currencies table name
     *
     * @var string
     */
    protected $table_name;

    /**
     * Create a new migration instance.
     */
    public function __construct()
    {
        $this->table_name = config('currency.drivers.database.table');
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable($this->table_name)) {
             Schema::drop($this->table_name);
        }

        Schema::create($this->table_name, function ($table) {
            $table->increments('id')->unsigned();
            $table->string('name');
            $table->string('code', 10)->index();
            $table->string('symbol', 25);
            $table->string('format', 50);
            $table->string('exchange_rate');
            $table->boolean('active')->default(false);
            $table->timestamps();
        });
        
        /* Artisan::call('currency:manage', ['' => 'add', '' => 'USD']);
        Artisan::call('currency:manage', ['' => 'add', '' => 'EUR']);
        Artisan::call('currency:manage', ['' => 'add', '' => 'GBP']);
        Artisan::call('currency:manage', ['' => 'add', '' => 'HKD']);
        Artisan::call('currency:manage', ['' => 'add', '' => 'JPY']);
        Artisan::call('currency:manage', ['' => 'add', '' => 'RUB']);
        Artisan::call('currency:manage', ['' => 'add', '' => 'INR']);
        Artisan::call('currency:manage', ['' => 'add', '' => 'CNY']); */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->table_name);
    }
}
