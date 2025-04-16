<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
// use RedisUser;

use Auth;
use knet\Helpers\RedisUser as RedisUser;

class RncIso extends Model
{
    protected $table = 'isornc';
    public $timestamps = false;
    // protected $primaryKey = 'codice';
    // public $incrementing = false;
    protected $connection = '';
    protected $dates = ['datareg', 'datainit', 'dataend', 'vappdate', 'veffdate'];

    public function __construct($attributes = array())
    {
        self::boot();
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }

    // Scope that garante to find only Supplier from anagrafe
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('isornc', function (Builder $builder) {
            $builder->where('codfor', 'LIKE', 'C%');
        });

        switch (RedisUser::get('role')) {
            case 'agent':
                static::addGlobalScope('agent', function (Builder $builder) {
                    $builder->whereHas('client', function ($query) {
                        $query->where('agente', RedisUser::get('codag'));
                    })->where('u_pub', true);
                });
                break;
            case 'superAgent':
                static::addGlobalScope('superAgent', function (Builder $builder) {
                    $builder->whereHas('client', function ($query) {
                        $query->whereHas('agent', function ($q) {
                            $q->where('u_capoa', RedisUser::get('codag'));
                        });
                    })->where('u_pub', true);
                    
                });
                break;
            case 'client':
                static::addGlobalScope('client', function (Builder $builder) {
                    $builder->where('codfor', RedisUser::get('codcli'))->where('u_pub', true);
                });
                break;

            default:
                break;
        }
    }

    // GETTER And SETTER
    public function getSeverityAttribute(){
        switch ($this->difett) {
            case 1:
                return '1 - Basso';
                break;

            case 2:
                return '2 - Medio';
                break;

            case 3:
                return '3 - Alto';
                break;
            
            default:
                return '-NC-';
                break;
        }
    }

    public function getTrasportoAttribute()
    {
        switch ($this->u_tc) {
            case 'C':
                return 'del Cliente';
                break;

            case 'F':
                return 'del Fornitore';
                break;

            case 'K':
                return 'di KronaKoblenz';
                break;

            case 'V':
                return 'del Vettore';
                break;

            default:
                return '-NC-';
                break;
        }
    }

    // JOIN Tables
    public function client()
    {
        return $this->belongsTo('knet\ArcaModels\Client', 'codfor', 'codice');
    }

    public function rncArts()
    {
        return $this->hasMany('knet\ArcaModels\RncArt', 'idrnc', 'id');
    }

    public function rncDocs()
    {
        return $this->hasMany('knet\ArcaModels\RncDoc', 'id_rnc', 'id');
    }

    public function rncCausa()
    {
        return $this->hasOne('knet\ArcaModels\RncCause', 'codice', 'causa');
    }

    public function rncTipoRapp()
    {
        return $this->hasOne('knet\ArcaModels\RncTipoRapp', 'codice', 'ctiporapp');
    }

    public function dipApertura()
    {
        return $this->hasOne('knet\ArcaModels\Dipendenti', 'codice', 'u_dipa');
    }

    public function dipAnalisi()
    {
        return $this->hasOne('knet\ArcaModels\Dipendenti', 'codice', 'u_dip1');
    }

    public function dipAttCorr()
    {
        return $this->hasOne('knet\ArcaModels\Dipendenti', 'codice', 'u_dip2');
    }

    public function dipChiusura()
    {
        return $this->hasOne('knet\ArcaModels\Dipendenti', 'codice', 'u_dipc');
    }

    public function vettore()
    {
        return $this->hasOne('knet\ArcaModels\Vettore', 'codice', 'u_vettore');
    }
}
