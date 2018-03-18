<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\Registry\Facades\Registry;

class StatABC extends Model
{
  protected $table = 'u_statabc';
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

    switch (Registry::get('role')) {
      case 'agent':
        static::addGlobalScope('agent', function(Builder $builder) {
            $builder->where('codag', Registry::get('codag'));
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
    return $this->belongsTo('knet\ArcaModels\Agent', 'codag', 'codice');
  }

  public function product(){
    return $this->belongsTo('knet\ArcaModels\Product', 'articolo', 'codice');
  }

  public function grpProd(){
    return $this->belongsTo('knet\ArcaModels\SubGrpProd', 'gruppo', 'codice');
  }

}
