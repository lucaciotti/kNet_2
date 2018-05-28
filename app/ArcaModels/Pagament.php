<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use RedisUser;

class Pagament extends Model
{
  protected $table = 'pagament';
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
    return $this->belongsTo('knet\ArcaModels\Client', 'pag', 'codice');
  }

  public function scadenza(){
    return $this->belongsTo('knet\ArcaModels\Scadenza', 'codpag', 'codice');
  }
}
