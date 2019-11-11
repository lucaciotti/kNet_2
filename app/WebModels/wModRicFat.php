<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use RedisUser;

class wModRicFat extends Model
{
    protected $table = 'w_modricfat';
    protected $dates = ['data_ricezione', 'created_at', 'updated_at', 'deleted_at'];
    protected $fillable = [
        'data_ricezione',
        'richiedente',
        'email_richiedente',
        'ragione_sociale',
        'codicecf',
        'tipologia_prodotto',
        'descr_pers',
        'url_pers',
        'system_kk',
        'system_other',
        'info_tecn_comm',
        'imballaggio',
        'um',
        'quantity',
        'periodo_ordinativi',
        'target_price',
        'ditta',
        'user_id'
    ];
    // protected $visible = [
    //     'id',
    // ];

    public function __construct($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }

    public static function boot()
    {
        parent::boot();
    }

    public function client()
    {
        return $this->belongsTo('knet\ArcaModels\Client', 'codicecf', 'codice');
    }

    public function user()
    {
        return $this->belongsTo('knet\User', 'user_id', 'id');
    }
}
