<?php

namespace knet\Http\Middleware;

use Auth;
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
        $request->session()->regenerate();
        if (Auth::check() && !$request->session()->has('user')){
          $user = $request->user();
          switch ($user->ditta) {
            case 'kNet_it':
              $settings = [
                'user.ditta_DB' => env('DB_CNCT_IT', 'kNet_it'),
                'user.location' => 'it',
                'user.role' => $user->role_name,
                'user.codag' => (string)$user->codag,
                'user.codcli' => (string)$user->codcli,
                'user.lang' => (string)$user->lang,
                'user.id' => $user->id
              ];
              break;
            case 'fr':
              $settings = [
                'user.ditta_DB' => env('DB_CNCT_FR', 'kNet_fr'),
                'user.location' => 'fr',
                'user.role' => $user->roles()->first()->name,
                'user.codag' => (string)$user->codag,
                'user.codcli' => (string)$user->codcli,
                'user.lang' => (string)$user->lang,
                'user.id' => $user->id
              ];
              break;
            case 'es':
              $settings = [
                'user.ditta_DB' => env('DB_CNCT_ES', 'kNet_es'),
                'user.location' => 'es',
                'user.role' => $user->roles()->first()->name,
                'user.codag' => (string)$user->codag,
                'user.codcli' => (string)$user->codcli,
                'user.lang' => (string)$user->lang,
                'user.id' => $user->id
              ];
              break;

            default:
              $request->session()->forget('user');
              abort(412, 'There\'s no Ditta!');
              break;
          }
          $request->session()->put($settings);
        }
        return $next($request);
    }
}
