<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedisUser;

class SubGrpProd extends Model
{
  protected $table = 'maggrp';
  public $timestamps = false;
  protected $primaryKey = 'codice';
  public $incrementing = false;
  protected $connection = '';

  public function __construct ($attributes = array())
  {
    self::boot();
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    $this->setConnection(RedisUser::get('ditta_DB'));
  }

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('subGruppo', function(Builder $builder) {
        $builder->whereRaw('length(codice)>3');
      });
  }
}
