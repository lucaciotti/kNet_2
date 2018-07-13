<?php

namespace knet;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;
    use LaratrustUserTrait;

    protected $fillable = [
        'name', 'nickname', 'email', 'password', 'ditta'
    ];

    protected $username = 'nickname';

    protected $connection = 'kNet';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $appends = ['role_name'];

    public function getRoleNameAttribute(){
        return $this->roles()->first()->name;
    }

    /* public function getDittaAttribute($value){
        return 'kNet_'.$value;
    } */

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
