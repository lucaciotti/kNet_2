<?php

namespace knet\WebModels;

use Illuminate\Database\Eloquent\Model;

use RedisUser;

class wRubrica extends Model
{
    protected $table = 'w_rubrica';
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];
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
}
