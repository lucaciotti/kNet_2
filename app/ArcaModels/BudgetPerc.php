<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use RedisUser;

use Auth;
use knet\Helpers\RedisUser as RedisUser;

class BudgetPerc extends Model
{
    protected $table = 'u_budget_perc';
    public $timestamps = false;
    // protected $primaryKey = 'codice';
    // public $incrementing = false;
    protected $connection = '';

    public function __construct($attributes = array())
    {
        self::boot();
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }
}
