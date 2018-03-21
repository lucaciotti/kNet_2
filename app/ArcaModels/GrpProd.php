<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\Registry\Facades\Registry;

class GrpProd extends Model
{
  protected $table = 'maggrp';
  public $timestamps = false;
  protected $primaryKey = 'codice';
  public $incrementing = false;
  protected $connection = '';

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('gruppo', function(Builder $builder) {
        $builder->whereRaw('length(codice)=3');
      });
  }

  public function __construct ($attributes = array())
  {
    self::boot();
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    // dd(Registry::get('ditta_DB'));
    $this->setConnection(session('user.ditta_DB'));
  }

}
