<?php
namespace knet\Helpers;

use Illuminate\Support\Facades\Redis;
use Auth;

class RedisUser
{
    private static $prefix = "user_config:";

    public static function exist(){
        return Redis::exists(static::$prefix.Auth::user()->id);
    }

    public static function store(){
        $user = Auth::user();
        switch ($user->ditta) {
            case 'it':
                $ditta = env('DB_CNCT_IT', 'kNet_it');
                break;
            case 'fr':
                $ditta = env('DB_CNCT_FR', 'kNet_fr');
                break;
            case 'es':
                $ditta = env('DB_CNCT_ES', 'kNet_es');
                break;

            default:
                abort(412, 'There\'s no Ditta!');
                break;
        }

        $settings = [
            'ditta_DB' => $ditta,
            'location' => 'it',
            'role' => $user->roles()->first()->name,
            'codag' => (string)$user->codag,
            'codcli' => (string)$user->codcli,
            'codforn' => (string)$user->codforn,
            'lang' => (string)$user->lang
        ];
        
        return Redis::hmset(static::$prefix.$user->id, $settings);
    }

    public static function getAll(){
        return Redis::hgeaAll(static::$prefix.Auth::user()->id);
    }

    /* public static function set($name) {
        return Redis::set(static::$prefix."1", $name);
    } */

    public static function get($name) {
        return Redis::hget(static::$prefix.Auth::user()->id, $name);
    }

}