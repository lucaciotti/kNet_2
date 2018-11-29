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
            $builder->where('datafine', '>=', Carbon::now())->orWhere('datafine', '=', '')->orWhereNull('datafine');
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
            $tipo = "Kubica";
        } elseif (substr($this->attributes['gruppomag'],0,1)=="B") {
            $tipo = "Koblenz";
        } elseif (substr($this->attributes['gruppomag'],0,1)=="A") {
            $tipo = "Krona";
        } elseif (substr($this->attributes['gruppomag'],0,1)=="C") {
            $tipo = "Grass";
        } elseif (substr($this->attributes['gruppomag'],0,1)=="2") {
            $tipo = "Campioni";
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

}
