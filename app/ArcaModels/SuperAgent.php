<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Torann\Registry\Facades\Registry;

class SuperAgent extends Model
{
    protected $table = 'agenti';
    public $timestamps = false;
    protected $primaryKey = 'codice';
    public $incrementing = false;
    protected $connection = '';

    protected static function boot()
    {
      parent::boot();

      static::addGlobalScope('manager', function(Builder $builder) {
          $builder->whereRaw('codice=u_capoa');
        });

      switch (Registry::get('role')) {
        case 'agent':
          static::addGlobalScope('agent', function(Builder $builder) {
              $builder->where('codice', Registry::get('codag'));
          });
          break;
        case 'superAgent':
          static::addGlobalScope('superAgent', function(Builder $builder) {
              $builder->where('codice', Registry::get('codag'));
          });
          break;
        case 'client':
          static::addGlobalScope('client', function(Builder $builder) {
              $builder->where('codice', Registry::get('codcli'));
          });
          break;

        default:
          break;
      }
    }

    public function __construct ($attributes = array())
    {
      parent::__construct($attributes);
      //Imposto la Connessione al Database
      // dd(Registry::get('ditta_DB'));
      $this->setConnection(Registry::get('ditta_DB'));
    }

    // JOIN Tables
    public function agent(){
      return $this->hasMany('knet\ArcaModels\Agent', 'codice', 'u_capoa');
    }

}
