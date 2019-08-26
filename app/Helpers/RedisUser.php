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
            'location' => $user->ditta,
            'role' => $user->roles()->first()->name,
            'codag' => (string)$user->codag,
            'codcli' => (string)$user->codcli,
            'codforn' => (string)$user->codfor,
            'lang' => (string)$user->lang,
            'isActive' => $user->isActive,
        ];
        
        Redis::hmset(static::$prefix.$user->id, $settings);

        return Redis::expire(static::$prefix.$user->id, 1800);
    }

    public static function getAll(){
        return Redis::hgetAll(static::$prefix.Auth::user()->id);
    }

    /* public static function set($name) {
        return Redis::set(static::$prefix."1", $name);
    } */

    public static function get($name) {
        return Redis::hget(static::$prefix.Auth::user()->id, $name);
    }

}