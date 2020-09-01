<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use knet\Helpers\RedisUser;

class AnagNote extends Model
{
    protected $table = 'u_anagnote';
    public $timestamps = false;
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $connection = '';

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }
}
