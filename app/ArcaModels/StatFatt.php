<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\Registry\Facades\Registry;

use Auth;

class StatFatt extends Model
{
  protected $table = 'u_statfatt';
  public $timestamps = false;
  protected $connection = '';

  public function __construct ($attributes = array())
  {
    self::boot();
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    // dd(Registry::get('ditta_DB'));
    $this->setConnection(Registry::get('ditta_DB'));
  }

  protected static function boot() {
    parent::boot();

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
    //         $builder->where('codice', Auth::user()->codcli);
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

  public function agent(){
    return $this->belongsTo('knet\ArcaModels\Agent', 'agente', 'codice');
  }

  public function grpProd(){
    return $this->belongsTo('knet\ArcaModels\GrpProd', 'gruppo', 'codice');
  }
}
