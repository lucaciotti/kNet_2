<?php

namespace knet\Providers;

use Illuminate\Support\ServiceProvider;

use Auth;
use Redis;

class RedisUserServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        require_once app_path() . '/Helpers/RedisUser.php';
    }
}
