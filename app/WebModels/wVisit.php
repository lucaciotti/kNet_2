<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use RedisUser;

class wVisit extends Model
{
  use LogsActivity;

  protected $table = 'w_visite';
  protected $dates = ['data', 'created_at', 'updated_at'];
  protected $fillable = ['codicecf', 'user_id', 'data', 'tipo', 'descrizione', 'note', 'rubri_id'];
  protected $connection = '';

  public function __construct ($attributes = array())
  {
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    $this->setConnection(RedisUser::get('ditta_DB'));
  }

  public function user(){
    return $this->hasOne('knet\User', 'id', 'user_id');
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
