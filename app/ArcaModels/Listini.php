<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedisUser;
use Carbon\Carbon;

class Listini extends Model
{
    protected $table = 'listini';
    public $timestamps = false;
    public $incrementing = false;
    protected $connection = '';
    protected $dates = ['dataini', 'datafine'];

    public function __construct ($attributes = array())
    {
      parent::__construct($attributes);
      //Imposto la Connessione al Database
      $this->setConnection(RedisUser::get('ditta_DB'));
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('attivo', function(Builder $builder) {
            $builder->where('datafine', '>=', Carbon::now())->orWhere('datafine', '=', '')->orWhereNull('datafine');
        });

        switch (RedisUser::get('role')) {
            case 'agent':
                static::addGlobalScope('agent', function(Builder $builder) {
                    $builder->whereHas('client', function ($query){
                        $query->where('agente', RedisUser::get('codag'));
                    });
                });
                break;
            case 'agent':
                static::addGlobalScope('agent', function(Builder $builder) {
                    $builder->whereHas('client', function ($query){
                        $query->where('agente', RedisUser::get('codag'));
                    });
                });
                break;
          /* case 'superAgent':
            static::addGlobalScope('superAgent', function(Builder $builder) {
              $builder->where('codice', RedisUser::get('codag'))->orWhere('u_capoa', RedisUser::get('codag'));
            });
            break; */

            default:
                break;
        }
    }
/* 
    public function getCodiceAttribute($value){
      return (string)$value;
    }
 */
    // JOIN Tables
    public function client(){
      return $this->hasMany('knet\ArcaModels\Client', 'codclifor', 'codice');
    }
}
