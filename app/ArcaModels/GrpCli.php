<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use RedisUser;

class GrpCli extends Model
{
  protected $table = 'cligrp';
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

  // JOIN Tables
  public function client(){
    return $this->hasMany('knet\ArcaModels\Client', 'gruppolist', 'codice');
  }

  public function listPersCli(){
    return $this->hasMany('knet\ArcaModels\Listini', 'gruppocli', 'codice');
  }
}
