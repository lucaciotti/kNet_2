<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use RedisUser;

use Auth;
use knet\Helpers\RedisUser as RedisUser;

class RncTipoRapp extends Model
{
    protected $table = 'isornt';
    public $timestamps = false;
    protected $primaryKey = 'codice';
    public $incrementing = false;

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }
}
