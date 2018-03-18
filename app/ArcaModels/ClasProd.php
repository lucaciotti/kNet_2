<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\Registry\Facades\Registry;

class ClasProd extends Model
{
  protected $table = 'magcls';
  public $timestamps = false;
  protected $primaryKey = 'codice';
  public $incrementing = false;
  protected $connection = '';

  public function __construct ($attributes = array())
  {
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    // dd(Registry::get('ditta_DB'));
    $this->setConnection(Registry::get('ditta_DB'));
  }

  // JOIN Tables LEN(column_name)

  protected static function boot()
  {
    parent::boot();

    static::addGlobalScope('classe', function(Builder $builder) {
        $builder->whereRaw('length(codice)=3');
    });
  }
}
