<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use RedisUser;

class Nazione extends Model
{
  protected $table = 'nazioni';
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
    return $this->belongsTo('knet\ArcaModels\Client', 'codice', 'codnazione');
  }
}
