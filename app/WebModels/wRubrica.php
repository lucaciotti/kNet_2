<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

use RedisUser;

class wRubrica extends Model
{
    protected $table = 'w_rubrica';
    protected $dates = ['created_at', 'updated_at', 'deleted_at','date_nextvisit', 'date_lastvisit'];
    protected $fillable = ['descrizion',
                        'telefono',
                        'sitoweb',
                        'settore',
                        'persdacont',
                        'email',
                        'indirizzo',
                        'localita',
                        'prov',
                        'cap',
                        'regione',
                        'codnazione', 
                        'partiva',
                        'codfiscale',
                        'legalerapp',
                        'agente',
                        'nDipendenti', 
                        'codicecf', 
                        'prod_mobili', 
                        'prod_porte',
                        'prod_portefinestre', 
                        'prod_cucine', 
                        'prod_other', 
                        'prod_note', 
                        'wants_info', 
                        'wants_tryKK', 
                        'know_kk', 
                        'isKkBuyer', 
                        'vote', 
                        'code_ateco', 
                        'date_nextvisit',
                        'user_id'];

    
                        public function __construct ($attributes = array())
    {
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }

    // Scope that garante to find only Client from anagrafe
    protected static function boot()
    {
        parent::boot();

        switch (RedisUser::get('role')) {
            case 'agent':
                static::addGlobalScope('agent', function(Builder $builder) {
                    $builder->where('agente', RedisUser::get('codag'));
                });
                break;
            case 'superAgent':
                static::addGlobalScope('superAgent', function(Builder $builder) {
                $builder->whereHas('agent', function ($query){
                    $query->where('u_capoa', RedisUser::get('codag'));
                    });
                });
                break;
            /* case 'client':
                static::addGlobalScope('client', function(Builder $builder) {
                    $builder->where('codice', RedisUser::get('codcli'));
                });
                break; */

            default:
                break;
        }
    }

    public function user(){
        return $this->hasOne('knet\User', 'id', 'user_id');
    }

    public function agent(){
      return $this->belongsTo('knet\ArcaModels\Agent', 'agente', 'codice');
    }

    public function client(){
      return $this->hasOne('knet\ArcaModels\Client', 'codice', 'codicecf');
    }
}
