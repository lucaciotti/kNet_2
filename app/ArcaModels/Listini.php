<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedisUser;
use Carbon\Carbon;

class Listini extends Model
{
    protected $table = 'listini';
    public $timestamps = false;
    public $incrementing = false;
    protected $connection = '';
    protected $dates = ['dataini', 'datafine'];
    protected $appends = ['master_grup', 'tipo_prod'];

    public function __construct ($attributes = array())
    {
      parent::__construct($attributes);
      //Imposto la Connessione al Database
      $this->setConnection(RedisUser::get('ditta_DB'));
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('attivo', function(Builder $builder) {
            if(RedisUser::get('ditta_DB')=='kNet_es' || (RedisUser::get('ditta_DB')=='kNet_it' && RedisUser::get('codag')=='002'))
            {
                $builder->where('datafine', '>=', new Carbon('first day of last year'))->orWhere('datafine', '=', '')->orWhereNull('datafine');
            } else {
                $builder->where('datafine', '>=', Carbon::now())->orWhere('datafine', '=', '')->orWhereNull('datafine');
            }
        });

        switch (RedisUser::get('role')) {
            case 'agent':
                static::addGlobalScope('agent', function(Builder $builder) {
                    $builder->whereHas('client', function ($query){
                        $query->where('agente', RedisUser::get('codag'));
                    })->orWhereHas('grpCli', function($queryGrp){
                        $queryGrp->whereHas('client', function($query){
                            $query->where('agente', RedisUser::get('codag'));
                        });
                    });
                });
                break;
            case 'client':
                static::addGlobalScope('client', function(Builder $builder) {
                    $builder->where('codclifor', RedisUser::get('codcli'));
                });
                break;
          case 'superAgent':
            static::addGlobalScope('superAgent', function(Builder $builder) {
                    $builder->whereHas('client', function ($query){
                        // $query->withoutGlobalScope('agent')->withoutGlobalScope('client');
                        $query->whereHas('agent', function ($q){
                            // $q->withoutGlobalScope('agent')->withoutGlobalScope('client');
                            $q->where('u_capoa', RedisUser::get('codag'));
                        });
                    })->orWhereHas('grpCli', function($queryGrp){
                        $queryGrp->whereHas('client', function($query){
                            $query->whereHas('agent', function ($q){
                                $q->where('u_capoa', RedisUser::get('codag'));
                            });
                        });
                    });
                });
            break;

            default:
                break;
        }
    }

    public function getMasterGrupAttribute(){
        return substr($this->attributes['gruppomag'],0,3);
    }

    public function getTipoProdAttribute(){
        if (substr($this->attributes['gruppomag'],0,3)=="B06"){
            $tipo = "KUBICA";
        } elseif (substr($this->attributes['gruppomag'],0,1)=="B") {
            $tipo = "KOBLENZ";
        } elseif (substr($this->attributes['gruppomag'],0,1)=="A") {
            $tipo = "KRONA";
        } elseif (substr($this->attributes['gruppomag'],0,1)=="C") {
            $tipo = "GRASS";
        } elseif (substr($this->attributes['gruppomag'],0,1)=="2") {
            $tipo = "CAMPIONI";
        } elseif (substr($this->attributes['gruppomag'],0,2)=="D0") {
            $tipo = "PLANET";
        } else {
            $tipo = "KK";
        }
        return $tipo;
    }

    // JOIN Tables
    public function client(){
      return $this->hasOne('knet\ArcaModels\Client', 'codice', 'codclifor');
    }

    public function grpCli(){
      return $this->hasOne('knet\ArcaModels\GrpCli', 'codice', 'gruppocli');
    }

    public function product(){
        return $this->belongsTo('knet\ArcaModels\Product', 'codicearti', 'codice');
    }

    public function masterProd(){
        return $this->hasOne('knet\ArcaModels\GrpProd', 'codice', 'master_grup');
    }

    public function grpProd(){
        return $this->hasOne('knet\ArcaModels\SubGrpProd', 'codice', 'gruppomag');
    }
    
    public function wListOk(){
        return $this->hasOne('knet\WebModels\wListiniOk', 'listini_id', 'id');
    }

}
