<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\Traits\LogsActivity;
use RedisUser;

class wDocRow extends Model
{
  use LogsActivity;

    protected $table = 'w_docrig';

    protected $dates = ['dataconseg', 'u_dtpronto'];
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

    public function product(){
      return $this->belongsTo('knet\ArcaModels\Product', 'codicearti', 'codice');
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
          return 'wDocRow on '. $this->getConnectionName() .' Id:"' . $this->id . '" was created ' .$this->toJson();
          break;
        case 'updated':
          return 'wDocRow on '. $this->getConnectionName() .' Id:"' . $this->id . '" was updated ' .json_encode($this->getDirty());
          break;
        case 'deleted':
          return 'wDocRow on '. $this->getConnectionName() .' Id:"' . $this->id . '" was deleted';
          break;

        default:
          return 'wDocRow on '. $this->getConnectionName() .' Id:"' . $this->id . '" was ??';
          break;
      }
    }

}
