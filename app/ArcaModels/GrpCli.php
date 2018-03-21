<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Torann\Registry\Facades\Registry;

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
    // dd(Registry::get('ditta_DB'));
    $this->setConnection(session('user.ditta_DB'));
  }

  // JOIN Tables
  public function client(){
    return $this->belongsTo('knet\ArcaModels\Client', 'gruppolist', 'codice');
  }
}
