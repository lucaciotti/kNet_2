<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use RedisUser;

use Auth;
use knet\Helpers\RedisUser as RedisUser;

class TargetAg extends Model
{
    protected $table = 'u_targetag';
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

        static::addGlobalScope('default', function (Builder $builder) {
            $builder->where('codage', '!=', '000')->where('codage', '!=', '00')->where('codage', '!=', 'DIR')->where('codage', '!=', 'Z00');
        });

        switch (RedisUser::get('role')) {
            case 'agent':
                static::addGlobalScope('agent', function (Builder $builder) {
                    $builder->where('codage', RedisUser::get('codag'));
                });
                break;
            case 'superAgent':
                static::addGlobalScope('superAgent', function (Builder $builder) {
                    $builder->whereHas('agent', function ($query) {
                        $query->where('u_capoa', RedisUser::get('codag'));
                    });
                });
                break;

            default:
                break;
        }
    }


    // JOIN Tables
    public function agent()
    {
        return $this->hasOne('knet\ArcaModels\Agent', 'codice', 'codage');
    }
}
