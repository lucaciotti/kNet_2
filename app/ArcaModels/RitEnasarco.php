<?php

namespace knet\ArcaModels;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use RedisUser;

class RitEnasarco extends Model
{
    protected $table = 'rit_enasarco';
    public $timestamps = false;
    protected $connection = '';

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
            $builder->where('fornitore', RedisUser::get('codforn'));
        });
    }

}
