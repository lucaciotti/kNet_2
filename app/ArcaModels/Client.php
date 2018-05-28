<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use RedisUser;

use Request;
// use knet\User;

class Client extends Model
{
    protected $table = 'anagrafe';
    public $timestamps = false;
    protected $primaryKey = 'codice';
    public $incrementing = false;
    protected $connection = '';

    // Scope that garante to find only Client from anagrafe
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('clients', function(Builder $builder) {
            $builder->where('codice', 'like', 'C%');
        });

        switch (RedisUser::get('role')) {
          case 'agent':
            static::addGlobalScope('agent', function(Builder $builder) {
                $builder->where('agente', RedisUser::get('codag'));
            });
            break;
          case 'superAgent':
            static::addGlobalScope('superAgent', function(Builder $builder) {
              $builder->whereHas('agent', function ($query){
                  $query->where('u_capoa', RedisUser::get('codag'));
                });
            });
            break;
          case 'client':
            static::addGlobalScope('client', function(Builder $builder) {
                $builder->where('codice', RedisUser::get('codcli'));
            });
            break;

          default:
            break;
        }
    }

    public function __construct ($attributes = array())
    {
      self::boot();
      parent::__construct($attributes);
      //Imposto la Connessione al Database
      $this->setConnection(RedisUser::get('ditta_DB'));
    }

    // JOIN Tables
    public function doccli(){
      return $this->hasMany('knet\ArcaModels\DocCli', 'codicecf', 'codice');
    }

    public function agent(){
      return $this->belongsTo('knet\ArcaModels\Agent', 'agente', 'codice');
    }

    public function clasCli(){
      return $this->hasOne('knet\ArcaModels\ClasCli', 'codice', 'classe');
    }

    public function grpCli(){
      return $this->hasOne('knet\ArcaModels\GrpCli', 'codice', 'gruppolist');
    }

    public function detSect(){
      return $this->hasOne('knet\ArcaModels\Settore', 'codice', 'settore');
    }

    public function detStato(){
      return $this->hasOne('knet\ArcaModels\StatoCf', 'codice', 'statocf');
    }

    public function detZona(){
      return $this->hasOne('knet\ArcaModels\Zona', 'codice', 'zona');
    }

    public function detProv(){
      return $this->hasOne('knet\ArcaModels\Provinc', 'codice', 'prov');
    }

    public function detNation(){
      return $this->hasOne('knet\ArcaModels\Nazione', 'codice', 'codnazione');
    }

    public function detPag(){
      return $this->hasOne('knet\ArcaModels\Pagament', 'codice', 'pag');
    }

    public function scadenza(){
      return $this->hasMany('knet\ArcaModels\ScadCli', 'codcf', 'codice');
    }

    //Multator
    // public function getDescrizionAttribute($value)
    // {
    //     return ucfirst(strtolower($value));
    // }

}
