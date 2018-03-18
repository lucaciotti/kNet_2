<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\Registry\Facades\Registry;

use Auth;

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
    // dd(Registry::get('ditta_DB'));
    $this->setConnection(Registry::get('ditta_DB'));
  }

  // Scope that garante to find only Supplier from anagrafe
  protected static function boot()
  {
      parent::boot();

      static::addGlobalScope('doccli', function(Builder $builder) {
          $builder->where('codicecf', 'LIKE', 'C%');
      });

      // if (Auth::check()){
      //   if (Auth::user()->hasRole('agent')){
      //     static::addGlobalScope('agent', function(Builder $builder) {
      //         $builder->where('agente', Auth::user()->codag);
      //     });
      //   }
      //   if (Auth::user()->hasRole('superAgent')){
      //     static::addGlobalScope('superAgent', function(Builder $builder) {
      //       $builder->whereHas('agent', function ($query){
      //           $query->where('u_capoa', Auth::user()->codag);
      //         });
      //     });
      //   }
      //   if (Auth::user()->hasRole('client')){
      //     static::addGlobalScope('client', function(Builder $builder) {
      //         $builder->where('codicecf', Auth::user()->codcli);
      //     });
      //   }
      // }
      switch (Registry::get('role')) {
        case 'agent':
          static::addGlobalScope('agent', function(Builder $builder) {
              $builder->where('agente', Registry::get('codag'));
          });
          break;
        case 'superAgent':
          static::addGlobalScope('superAgent', function(Builder $builder) {
            $builder->whereHas('agent', function ($query){
                $query->where('u_capoa', Registry::get('codag'));
              });
          });
          break;
        case 'client':
          static::addGlobalScope('client', function(Builder $builder) {
              $builder->where('codicecf', Registry::get('codcli'));
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
    return $this->hasOne('knet\ArcaModels\ScadCli', 'id_doc', 'id');
  }

  public function wDdtOk(){
    return $this->hasOne('knet\WebModels\wDdtOk', 'id_testa', 'id');
  }

  // public function destinaz(){
  //   return $this->hasOne('knet\ArcaModels\Destinaz', 'codicedes', 'destdiv')->join('codicecf', $this->codicecf);
  // }

  //Multator
  // public function getDatadocAttribute($value)
  // {
  //    return $value->format('m/d/Y');
  // }

}
