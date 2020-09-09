<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use RedisUser;

use Auth;
use knet\Helpers\RedisUser as RedisUser;

class DocCli extends Model
{
  protected $table = 'doctes';
  public $timestamps = false;
  // protected $primaryKey = 'codice';
  // public $incrementing = false;
  protected $connection = '';
  protected $dates = ['datadoc', 'v1data'];

  public function __construct ($attributes = array())
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

      static::addGlobalScope('doccli', function(Builder $builder) {
          $builder->where('codicecf', 'LIKE', 'C%');
      });

      switch (RedisUser::get('role')) {
        case 'agent':
            static::addGlobalScope('agent', function (Builder $builder) {
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
              $builder->where('codicecf', RedisUser::get('codcli'))->where('tipodoc', '!=', 'AA');
          });
          break;

        default:
          break;
      }
  }

  // JOIN Tables
  public function client(){
    return $this->belongsTo('knet\ArcaModels\Client', 'codicecf', 'codice');
  }

  public function docrow(){
    return $this->hasMany('knet\ArcaModels\DocRow', 'id_testa', 'id');
  }

  public function agent(){
    return $this->hasOne('knet\ArcaModels\Agent', 'codice', 'agente');
  }

  public function vettore(){
    return $this->hasOne('knet\ArcaModels\Vettore', 'codice', 'vettore1');
  }

  public function detBeni(){
    return $this->hasOne('knet\ArcaModels\AspBeni', 'codice', 'aspbeni');
  }

  public function scadenza(){
    return $this->hasMany('knet\ArcaModels\ScadCli', 'id_doc', 'id');
  }

  public function wDdtOk(){
    return $this->hasOne('knet\WebModels\wDdtOk', 'id_testa', 'id');
  }

  // public function destCodDes(){
  //   return $this->hasMany('knet\ArcaModels\Destinaz', 'destdiv', 'codicedes');
  // }
  // public function destCodCF(){
  //   return $this->hasMany('knet\ArcaModels\Destinaz', 'codicecf', 'codicecf');
  // }
  // public function destinaz(){
  //   return $this->destCodDes->merce($this->destCodCF);
  // }

  //Multator
  // public function getDatadocAttribute($value)
  // {
  //    return $value->format('m/d/Y');
  // }

}
