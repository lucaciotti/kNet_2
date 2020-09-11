<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use knet\Helpers\RedisUser;

class ProdLang extends Model
{
  protected $table = 'maglang';
  public $timestamps = false;
  protected $primaryKey = 'codicearti';
  public $incrementing = false;
  protected $connection = '';

  // Scope that garante to find only Supplier from anagrafe
  protected static function boot()
  {
      parent::boot();
  }

  public function __construct ($attributes = array())
  {
    self::boot();
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    $this->setConnection(RedisUser::get('ditta_DB'));
  }

  

}