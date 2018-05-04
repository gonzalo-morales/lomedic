<?php
namespace App\Http\Middleware;

use Closure;
use App\Http\Models\Administracion\Empresas;

class Company
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
        $url = session()->get('url.intended');
        $aurl = explode('/',$url);
        
        $company = isset($aurl[3]) ? $aurl[3] : $request->company;

        if(!empty($company)) {
            $Empresas = collect(Empresas::isActivo()->get()->toarray());
            
            $request->empresa  = (object) $Empresas->where('conexion',$company)->first();
            $request->empresas = (object) $Empresas->where('conexion','!=', null)->where('conexion', '!=', $company);
        }
        elseif(\Auth::guard()->check()) {
            $Empresas = collect(Empresas::isActivo()->get()->toarray());
            
            $request->empresa  = (object) $Empresas->where('id_empresa',\Auth::user()->fk_id_empresa)->first();
            $request->empresas = (object) $Empresas->where('id_empresa','!=', \Auth::user()->fk_id_empresa)->where('conexion', '!=', null);
        }
        
        return $next($request);
    }
}