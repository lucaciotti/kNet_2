<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Auth;

use Spatie\Activitylog\Traits\LogsActivity;
use RedisUser;

class wDocCli extends Model 
{
  use LogsActivity;

    protected $table = 'w_doctes';
    protected $dates = ['datadoc', 'v1data'];
    protected $connection = '';

    public function __construct ($attributes = array())
    {
      self::boot();
      parent::__construct($attributes);
      //Imposto la Connessione al Database
      $this->setConnection(RedisUser::get('ditta_DB'));
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('doccli', function(Builder $builder) {
            $builder->where('codicecf', 'LIKE', 'C%');
        });

        switch (RedisUser::get('role')) {
        case 'agent':
          static::addGlobalScope('agent', function(Builder $builder) {
              $builder->where('agente', RedisUser::get('codag'));
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
              $builder->where('codicecf', RedisUser::get('codcli'));
          });
          break;

        default:
          break;
      }
    }

    // JOIN Tables
    public function client(){
      return $this->belongsTo('knet\ArcaModels\Client', 'codicecf', 'codice');
    }

    public function docrow(){
      return $this->hasMany('knet\ArcaModels\DocRow', 'id_testa', 'id');
    }

    /**
     * Get the message that needs to be logged for the given event name.
     *
     * @param string $eventName
     * @return string
     */
    public function getActivityDescriptionForEvent($eventName){
      switch ($eventName) {
        case 'created':
          return 'wDocCli on '. $this->getConnectionName() .' Id:"' . $this->id . '" was created ' .$this->toJson();
          break;
        case 'updated':
          return 'wDocCli on '. $this->getConnectionName() .' Id:"' . $this->id . '" was updated ' .json_encode($this->getDirty());
          break;
        case 'deleted':
          return 'wDocCli on '. $this->getConnectionName() .' Id:"' . $this->id . '" was deleted';
          break;

        default:
          return 'wDocCli on '. $this->getConnectionName() .' Id:"' . $this->id . '" was ??';
          break;
      }
    }
}
