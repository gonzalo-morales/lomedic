<?php
namespace App\Http\Middleware;

use Closure;
use App;
use Config;
use Cookie;

class Locale
{
    /**
     * Handle an incoming request.
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locales = config('app.locales');
        $cookie = Cookie::get('app_locale');
        $browse = substr($request->server('HTTP_ACCEPT_LANGUAGE'),0,2);
        $default = config('app.locale');
        
        $lang = isset($locales[$cookie]) ? $cookie : (isset($locales[$browse]) ? $browse : $default);
        App::setLocale($lang);
        return $next($request);
    }
}