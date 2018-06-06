<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;
use RedisUser;

class RitMov extends Model
{
    protected $table = 'rit_mov';
    protected $connection = '';
    protected $dates = ['ftdatadoc'];
    protected $appends = ['esercizio'];

    public function __construct ($attributes = array())
    {
        self::boot();
        parent::__construct($attributes);
        //Imposto la Connessione al Database
        $this->setConnection(RedisUser::get('ditta_DB'));
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('myRit', function(Builder $builder) {
            $builder->where('codfor', RedisUser::get('codforn'));
        });
    }

    public function getEsercizioAttribute(){
        return (new Carbon($this->ftdatadoc))->year;
    }

    public function ritana(){
        return $this->belongsTo('knet\ArcaModels\RitAna', 'codfor', 'codfor');
    }
}
