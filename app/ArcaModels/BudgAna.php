<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use RedisUser;

use Auth;
use knet\Helpers\RedisUser as RedisUser;

class BudgAna extends Model
{
    protected $table = 'u_budgana';
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

    // Scope that garante to find only Supplier from anagrafe
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('budgcli', function (Builder $builder) {
            $builder->where('codice', 'LIKE', 'C%')->where(function ($query) {
                $query->orWhere('u_budg1', '!=', 0);
                $query->orWhere('u_budg2', '!=', 0);
                $query->orWhere('u_budg3', '!=', 0);
                $query->orWhere('u_kobudg1', '!=', 0);
                $query->orWhere('u_kobudg2', '!=', 0);
                $query->orWhere('u_kobudg3', '!=', 0);
            });
        });

        switch (RedisUser::get('role')) {
            case 'agent':
                static::addGlobalScope('agent', function (Builder $builder) {
                    $builder->whereHas('client', function ($query) {
                        $query->where('agente', RedisUser::get('codag'));
                    })->orWhereHas('grpCli', function ($queryGrp) {
                        $queryGrp->whereHas('client', function ($query) {
                            $query->where('agente', RedisUser::get('codag'));
                        });
                    });
                });
                break;
            case 'client':
                static::addGlobalScope('client', function (Builder $builder) {
                    $builder->where('codice', RedisUser::get('codcli'));
                });
                break;
            case 'superAgent':
                static::addGlobalScope('superAgent', function (Builder $builder) {
                    $builder->whereHas('client', function ($query) {
                        // $query->withoutGlobalScope('agent')->withoutGlobalScope('client');
                        $query->whereHas('agent', function ($q) {
                            // $q->withoutGlobalScope('agent')->withoutGlobalScope('client');
                            $q->where('u_capoa', RedisUser::get('codag'));
                        });
                    });
                });
                break;

            default:
                break;
        }
    }


    // JOIN Tables
    public function client()
    {
        return $this->hasOne('knet\ArcaModels\Client', 'codice', 'codice');
    }
}
