<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use RedisUser;

use Auth;
use knet\Helpers\RedisUser as RedisUser;

class RncDoc extends Model
{
    protected $table = 'u_rncdoc';
    public $timestamps = false;
    // protected $primaryKey = 'codice';
    // public $incrementing = false;
    protected $dates = ['datadoc'];

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }


    // JOIN Tables
    public function rnc()
    {
        return $this->belongsTo('knet\ArcaModels\RncIso', 'id_rnc', 'id');
    }

    public function doccli()
    {
        return $this->belongsTo('knet\ArcaModels\DocCli', 'id_doc', 'id');
    }
}
