<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Torann\Registry\Facades\Registry;

class Agent extends Model
{
    protected $table = 'agenti';
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

    // JOIN Tables
    public function client(){
      return $this->hasMany('knet\ArcaModels\Client', 'codice', 'agente');
    }

    public function manager(){
      return $this->hasOne('knet\ArcaModels\SuperAgent', 'codice', 'u_capoa');
    }

    public function scadenza(){
      return $this->hasMany('knet\ArcaModels\Scadenza', 'codice', 'codag');
    }

}
