<?php
namespace App\Http\Middleware;

use Closure;
use App;
use Config;
use Session;

class locale
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
        $locales = config('app.locales');
        $session = Session::get('locale');
        $browse = substr($request->server('HTTP_ACCEPT_LANGUAGE'),0,2);
        $default = config('app.locale');
        
        $lang = isset($locales[$session]) ? $session : (isset($locales[$browse]) ? $browse : $default);

        App::setLocale($lang);
        return $next($request);
    }
}