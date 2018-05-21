<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\Registry\Facades\Registry;
use RedisUser;

class SubClasProd extends Model
{
  protected $table = 'magcls';
  public $timestamps = false;
  protected $primaryKey = 'codice';
  public $incrementing = false;
  protected $connection = '';

  public function __construct ($attributes = array())
  {
    self::boot();
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    // dd(Registry::get('ditta_DB'));
    $this->setConnection(RedisUser::get('ditta_DB'));
  }

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('subClasse', function(Builder $builder) {
        $builder->whereRaw('length(codice)>3');
    });
  }
}
