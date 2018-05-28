<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedisUser;

use Auth;

class ScadCli extends Model
{
  protected $table = 'scadenze';
  public $timestamps = false;
  // protected $primaryKey = 'codice';
  // public $incrementing = false;
  protected $connection = '';
  protected $dates = ['datafatt', 'datascad', 'datasollec'];
  protected $appends = ['desc_pag'];

  // Scope that garante to find only Client from anagrafe
  protected static function boot()
  {
      parent::boot();

      static::addGlobalScope('scadClient', function(Builder $builder) {
          $builder->where('codcf', 'like', 'C%');
      });

      switch (RedisUser::get('role')) {
        case 'agent':
          static::addGlobalScope('agent', function(Builder $builder) {
              $builder->where('codag', RedisUser::get('codag'));
          });
          break;
        case 'superAgent':
          static::addGlobalScope('superAgent', function(Builder $builder) {
            $builder->whereHas('agent', function ($query){
                $query->where('u_capoa', RedisUser::get('codag'));
              });
          });
          break;
        case 'client':
          static::addGlobalScope('client', function(Builder $builder) {
              $builder->where('codcf', RedisUser::get('codcli'));
          });
          break;

        default:
          break;
      }
  }

  public function __construct ($attributes = array())
  {
    self::boot();
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    $this->setConnection(RedisUser::get('ditta_DB'));
  }

  public function getDescPagAttribute()
    {
      $desc='none';
      switch ($this->attributes['tipo']) {
        case 'D':
          $desc=trans('scad.dirRem');
          break;
        case 'R':
          $desc=trans('scad.bnkRpt');;
          break;
        case 'T':
          $desc=trans('scad.blExc');;
          break;
        case 'P':
          $desc=trans('scad.iou');;
          break;
        case 'L':
          $desc=trans('scad.pstlPay');;
          break;
        case 'C':
          $desc=trans('scad.CoD');;
          break;
        case 'B':
          $desc=trans('scad.WiTr');;
          break;
        case 'A':
          $desc=trans('scad.otherPayment');;
          break;
        default:
          $desc='none';
      }
      return $desc;
    }

  public function client(){
    return $this->belongsTo('knet\ArcaModels\Client', 'codcf', 'codice');
  }

  public function docCli(){
    return $this->belongsTo('knet\ArcaModels\DocCli', 'id_doc', 'id');
  }

  public function pagament(){
    return $this->hasOne('knet\ArcaModels\Pagament', 'codice', 'codpag');
  }

  public function detSollec(){
    return $this->hasOne('knet\ArcaModels\Sollecito', 'codice', 'sollecito');
  }

  public function agent(){
    return $this->belongsTo('knet\ArcaModels\Agent', 'codag', 'codice');
  }

  public function storia(){
    return $this->belongsToMany('knet\ArcaModels\CreditStr', 'u_scadcre', 'id_scad', 'id_crediti', 'id', 'id');
  }
}
