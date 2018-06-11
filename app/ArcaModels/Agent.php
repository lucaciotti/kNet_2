<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedisUser;

class Agent extends Model
{
    protected $table = 'agenti';
    public $timestamps = false;
    protected $primaryKey = 'codice';
    public $incrementing = false;
    protected $connection = '';

    public function __construct ($attributes = array())
    {
      parent::__construct($attributes);
      //Imposto la Connessione al Database
      $this->setConnection(RedisUser::get('ditta_DB'));
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('default', function(Builder $builder) {
            $builder->where('codice', '!=', '')->where('codice', '!=', '00');
        });

        switch (RedisUser::get('role')) {
          case 'agent':
            static::addGlobalScope('agent', function(Builder $builder) {
                $builder->where('codice', RedisUser::get('codag'));
            });
            break;
          case 'superAgent':
            static::addGlobalScope('superAgent', function(Builder $builder) {
              $builder->where('codice', RedisUser::get('codag'))->orWhere('u_capoa', RedisUser::get('codag'));
            });
            break;

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
      return $this->hasMany('knet\ArcaModels\Client', 'codice', 'agente');
    }

    public function manager(){
      return $this->hasOne('knet\ArcaModels\SuperAgent', 'codice', 'u_capoa');
    }

    public function scadenza(){
      return $this->hasMany('knet\ArcaModels\Scadenza', 'codice', 'codag');
    }

    //Funzioni Speciali
    /* public function listAgents(){
      $lista = Self::select('codice', 'descrizion')->whereNull('u_dataini')->orderBy('codice')->get();
      
    } */

}
