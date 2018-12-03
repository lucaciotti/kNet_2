<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedisUser;
use Carbon\Carbon;

class wListiniOk extends Model
{
    protected $table = 'w_listok';
    protected $dates = ['created_at', 'updated_at'];
    protected $fillable = ['id', 'listini_id', 'esercizio'];
    protected $connection = '';

    public function __construct ($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }
    // JOIN Tables
    public function listini(){
        return $this->belongsTo('knet\ArcaModels\Listini', 'listini_id', 'id');
    }
}
