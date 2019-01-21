<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use RedisUser;

class wModCarp01 extends Model
{
    protected $table = 'w_mod_carp_01';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
    protected $fillable = ['rubri_id',
                        'prod_mobili',
                        'prod_porte',
                        'prod_portefinestre',
                        'prod_cucine',
                        'prod_other',
                        'prod_isMulti',
                        'prod_note',
                        'know_kk',
                        'isKkBuyer',
                        'yes_supplierType',
                        'yes_supplierName',
                        'yes_isInformato',
                        'not_why_prezzo',
                        'not_why_qualità',
                        'not_why_servizio',
                        'not_why_catalogo',
                        'not_why_noinfo',
                        'not_supplierType',
                        'not_supplierName',
                        'wants_tryKK',
                        'notryKK_note',
                        'wants_info',
                        'final_note',
                        'vote',
                        'user_id'];
    
    public function __construct ($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }

    
    public function contact(){
      return $this->belongsTo('knet\WebModels\wRubrica', 'rubri_id', 'id');
    }

    public function user(){
      return $this->belongsTo('knet\User', 'user_id', 'id');
    }

    public function sysBuyOfKK(){
        return $this->hasMany('knet\WebModels\wMCarp01_SysBuyOFOther', 'mcarp01_id', 'id');
      /* return $this->hasManyThrough(
            'knet\WebModels\wSysMkt',
            'knet\WebModels\wMCarp01_SysBuyOFKK',
            'mcarp01_id', // Foreign key on PivotModCarp table...
            'codice', // Foreign key on ModCarp table...
            'id', // Local key on this table...
            'sysmkt_cod' // Local key on PivotModCarp table...
        ); */
    }

    public function sysBuyOfOther(){
        return $this->hasMany('knet\WebModels\wMCarp01_SysBuyOFOther', 'mcarp01_id', 'id');
    }

    public function sysKnown(){
        return $this->hasMany('knet\WebModels\wMCarp01_SysBuyOFOther', 'mcarp01_id', 'id');
    }

    public function sysLiked(){
        return $this->hasMany('knet\WebModels\wMCarp01_SysBuyOFOther', 'mcarp01_id', 'id');
    }
}
