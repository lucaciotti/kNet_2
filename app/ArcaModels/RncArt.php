<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use RedisUser;

use Auth;
use knet\Helpers\RedisUser as RedisUser;

class RncArt extends Model
{
    protected $table = 'u_rncart';
    public $timestamps = false;
    // protected $primaryKey = 'codice';
    // public $incrementing = false;

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }


    // JOIN Tables
    public function rnc()
    {
        return $this->belongsTo('knet\ArcaModels\RncIso', 'idrnc', 'id');
    }

    public function product()
    {
        return $this->belongsTo('knet\ArcaModels\Product', 'codicearti', 'codice');
    }

    public function doccli()
    {
        return $this->belongsTo('knet\ArcaModels\DocCli', 'idtestadoc', 'id');
    }

    public function docrow()
    {
        return $this->belongsTo('knet\ArcaModels\DocRow', 'idrigadoc', 'id');
    }

    public function rncArtSt()
    {
        return $this->hasOne('knet\ArcaModels\RncArtSt', 'codice', 'statoart');
    }
}
