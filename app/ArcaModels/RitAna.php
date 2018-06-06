<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedisUser;

class RitAna extends Model
{
    protected $table = 'rit_ana';
    public $timestamps = false;
    protected $connection = '';
    protected $dates = ['datanasc'];

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

    public function ritmov(){
        return $this->hasMany('knet\ArcaModels\RitMov', 'codfor', 'codfor');
    }

}
