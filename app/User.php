<?php

namespace knet;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use LaratrustUserTrait;

    protected $fillable = [
        'name', 'nickname', 'email', 'password', 'ditta'
    ];

    protected $username = 'nickname';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function client(){
      return $this->hasOne('knet\ArcaModels\Client', 'codice', 'codcli');
    }

    public function agent(){
      return $this->hasOne('knet\ArcaModels\Agent', 'codice', 'codag');
    }
    /* 
    public function roles(){
        return $this->belongsToMany('knet\Role');
    } */
}
