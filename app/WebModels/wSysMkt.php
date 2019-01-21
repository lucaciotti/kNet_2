<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use RedisUser;

class wSysMkt extends Model
{
    protected $table = 'w_system_mkt';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['codice',
                        'livello',
                        'descrizione',
                        'url',
                        ];

    public function __construct ($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }

    public function sysLiked_mCarp01(){
         return $this->hasManyThrough(
            'knet\WebModels\wModCarp01',
            'knet\WebModels\wMCarp01_SysLiked',
            'sysmkt_cod', // Foreign key on PivotModCarp table...
            'id', // Foreign key on ModCarp table...
            'codice', // Local key on this table...
            'mcarp01_id' // Local key on PivotModCarp table...
        );
    }
}
