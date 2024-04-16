<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use Spatie\Activitylog\Traits\LogsActivity;
use RedisUser;

class wVisit extends Model
{
  use LogsActivity;

  protected $table = 'w_visite';
  protected $dates = ['data', 'created_at', 'updated_at'];
  protected $fillable = ['codicecf', 'user_id', 'data', 'tipo', 'descrizione', 'note', 'rubri_id', 'persona_contatto', 'funzione_contatto', 'conclusione', 'ordine', 'data_prox'];
  protected $connection = '';

  public function __construct ($attributes = array())
  {
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    $this->setConnection(RedisUser::get('ditta_DB'));
  }

  protected static function boot()
    {
        parent::boot();

        switch (RedisUser::get('role')) {
          case 'agent':
              static::addGlobalScope('agent', function (Builder $builder) {
                 $builder->whereHas('client', function ($query){
                  $query->where('agente', RedisUser::get('codag'));
                })->orWhereHas('rubri', function ($query){
                  $query->where('agente', RedisUser::get('codag'));
                });
              });
            break;
          case 'quality':
              static::addGlobalScope('agent', function (Builder $builder) {
                 $builder->whereHas('supplier', function ($query){
                  $query->where('agente', RedisUser::get('codag'));
                })->orWhereHas('rubri', function ($query){
                  $query->where('agente', RedisUser::get('codag'));
                });
              });
            break;
          case 'superAgent':
            static::addGlobalScope('superAgent', function(Builder $builder) {
              $builder->whereHas('client', function ($query){
                  $query->whereHas('agent', function ($q){
                    $q->where('u_capoa', RedisUser::get('codag'));
                  });
                })->orWhereHas('rubri', function ($query){
                  $query->whereHas('agent', function ($q){
                    $q->where('u_capoa', RedisUser::get('codag'));
                  });
                });
            });
            break;
          case 'client':
            static::addGlobalScope('client', function(Builder $builder) {
                $builder->where('codice', RedisUser::get('codcli'));
            });
            break;

          default:
            break;
        }
    }


  public function getTipoVisitAttribute(){
      switch ($this->attributes['tipo']) {
        case 'Meet':
          return trans('visit.eventMeeting');
          break;
        case 'Mail':
          return trans('visit.eventMail');
          break;
        case 'Prod':
          return trans('visit.eventProduct');
          break;
        case 'Scad':
          return trans('visit.eventDebt');
          break;
        case 'RNC':
          return trans('visit.eventRNC');
          break;
        default:
          return trans('visit.eventGeneric');
          break;          
      }
  }

  public function user(){
    return $this->hasOne('knet\User', 'id', 'user_id');
  }

  public function client(){
    return $this->hasOne('knet\ArcaModels\Client', 'codice', 'codicecf');
  }

  public function supplier(){
    return $this->hasOne('knet\ArcaModels\Supplier', 'codice', 'codicecf');
  }

  public function rubri(){
    return $this->hasOne('knet\WebModels\wRubrica', 'id', 'rubri_id');
  }

  /**
   * Get the message that needs to be logged for the given event name.
   */
  public function getActivityDescriptionForEvent($eventName){
    switch ($eventName) {
      case 'created':
        return 'Visit on '. $this->getConnectionName() .' Id:"' . $this->id . '" was created ' .$this->toJson();
        break;
      case 'updated':
        return 'Visit on '. $this->getConnectionName() .' Id:"' . $this->id . '" was updated ' .json_encode($this->getDirty());
        break;
      case 'deleted':
        return 'Visit on '. $this->getConnectionName() .' Id:"' . $this->id . '" was deleted';
        break;

      default:
        return 'Visit on '. $this->getConnectionName() .' Id:"' . $this->id . '" was ??';
        break;
    }
  }

}
