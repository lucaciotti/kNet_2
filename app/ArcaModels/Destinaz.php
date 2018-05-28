<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use RedisUser;

class Destinaz extends Model
{
  protected $table = 'destinaz';
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
  // public function docCli(){
  //   return $this->belongsTo('knet\ArcaModels\DocCli', 'codice', 'destdiv');
  // }
}
