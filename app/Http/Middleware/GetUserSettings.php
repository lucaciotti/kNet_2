<?php

namespace knet\Http\Middleware;

use Auth;
use RedisUser;
use Closure;

class GetUserSettings
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && !RedisUser::exist()){
          RedisUser::store();  
        }
        return $next($request);
    }
}
