<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
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

  protected static function boot()
  {
      parent::boot();

      if(RedisUser::get('ditta_DB')=='kNet_es' && RedisUser::get('codag')=='A6'){
        static::addGlobalScope('agent', function (Builder $builder) {
          $builder->where('gruppo', 'like', 'A%')->orWhere('gruppo', '');
        });
      } 
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

  public function descrLangEN()
  {
    return $this->hasOne('knet\ArcaModels\ProdLang', 'codicearti', 'codicearti')->where('codlingua', 'UK');
  }

}
