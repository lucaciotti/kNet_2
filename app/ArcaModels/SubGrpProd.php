<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\Registry\Facades\Registry;

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
    // dd(Registry::get('ditta_DB'));
    $this->setConnection(session('user.ditta_DB'));
  }

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('subGruppo', function(Builder $builder) {
        $builder->whereRaw('length(codice)>3');
      });
  }
}
