<?php

namespace knet\Http\Middleware;

use Auth;
use App;
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
        if (Auth::check() && (!RedisUser::exist() || !RedisUser::get('isActive'))){
            RedisUser::store();  
            return RedisUser::get('isActive') ? $next($request) : abort(503, 'Unauthorized action.');
        } else {
            return $next($request);
        }
    }
}
