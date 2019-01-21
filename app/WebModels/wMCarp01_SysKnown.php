<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use RedisUser;

class wMCarp01_SysKnown extends Model
{
    protected $table = 'w_mcarp01_sysKnown';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['type',
                        'mcarp01_id',
                        'sysmkt_cod'
                        ];
    
    public function __construct ($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }

    public function modCarp01(){
      return $this->belongsTo('knet\WebModels\wModCarp01', 'mcarp01_id', 'id');
    }

    public function sysMkt(){
      return $this->belongsTo('knet\WebModels\wSysMkt', 'sysmkt_cod', 'codice');
    }
}
