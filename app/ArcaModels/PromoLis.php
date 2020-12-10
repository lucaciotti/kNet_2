<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

use Carbon\Carbon;
use knet\Helpers\RedisUser as RedisUser;

class PromoLis extends Model
{
    protected $table = 'u_promolis';
    public $timestamps = false;
    // protected $primaryKey = 'id';
    public $incrementing = false;
    protected $connection = '';

    // protected $dates = ['dataini', 'datafine'];
    // protected $appends = ['master_clas', 'master_grup', 'listino', 'tipo_prod'];

    // Scope that garante to find only Supplier from anagrafe
    protected static function boot()
    {
        parent::boot();
    }

    public function __construct($attributes = array())
    {
        self::boot();
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }

    // JOIN Tables
    public function promo()
    {
        return $this->belongsTo('knet\ArcaModels\Promo', 'id_promo', 'id');
    }

    public function listini()
    {
        return $this->belongsTo('knet\ArcaModels\Listini', 'id_listino', 'id');
    }
}