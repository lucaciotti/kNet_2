<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use RedisUser;

class DocRow extends Model
{
  protected $table = 'docrig';
  public $timestamps = false;
  protected $connection = '';

  protected $dates = ['dataconseg', 'u_dtpronto'];

  public function __construct ($attributes = array())
  {
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    $this->setConnection(RedisUser::get('ditta_DB'));
  }

  // JOIN Tables
  public function doccli(){
    return $this->belongsTo('knet\ArcaModels\DocCli', 'id_testa', 'id');
  }

  public function docsup(){
    return $this->belongsTo('knet\ArcaModels\DocSup', 'id_testa', 'id');
  }

  public function product(){
    return $this->belongsTo('knet\ArcaModels\Product', 'codicearti', 'codice');
  }

}
