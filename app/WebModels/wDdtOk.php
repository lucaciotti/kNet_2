<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use RedisUser;

class wDdtOk extends Model
{
  use LogsActivity;

  protected $table = 'w_ddtok';
  protected $dates = ['created_at', 'updated_at'];
  protected $fillable = ['firma', 'note', 'id_testa', 'user_id'];
  protected $connection = '';

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

    /**
     * Get the message that needs to be logged for the given event name.
     *
     * @param string $eventName
     * @return string
     */
    public function getActivityDescriptionForEvent($eventName){
      switch ($eventName) {
        case 'created':
          return 'DdtOk on '. $this->getConnectionName() .' Id:"' . $this->id . '" was created ' .$this->toJson();
          break;
        case 'updated':
          return 'DdtOk on '. $this->getConnectionName() .' Id:"' . $this->id . '" was updated ' .json_encode($this->getDirty());
          break;
        case 'deleted':
          return 'DdtOk on '. $this->getConnectionName() .' Id:"' . $this->id . '" was deleted';
          break;

        default:
          return 'DdtOk on '. $this->getConnectionName() .' Id:"' . $this->id . '" was ??';
          break;
      }
    }
}
