<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Torann\Registry\Facades\Registry;

class CreditStr extends Model
{
  protected $table = 'crediti_st';
  public $timestamps = false;
  // protected $primaryKey = 'codice';
  // public $incrementing = false;
  protected $connection = '';

  protected $dates = ['datareg'];

  public function __construct ($attributes = array())
  {
    parent::__construct($attributes);
    //Imposto la Connessione al Database
    // dd(Registry::get('ditta_DB'));
    $this->setConnection(session('user.ditta_DB'));
  }

  public function scadenza(){
    return $this->belongsToMany('knet\ArcaModels\ScadCli', 'u_scadcre', 'id_crediti', 'id_scad', 'id', 'id');
  }
}
