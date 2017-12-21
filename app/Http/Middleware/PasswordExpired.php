<?php
namespace App\Http\Middleware;

use App\Http\Models\Administracion\Empresas;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Closure;

class PasswordExpired
{
    public function handle($request, Closure $next)
    {
        $user = $request->user();
        $fechapassword = new Carbon($user->fecha_cambio_password ? $user->fecha_cambio_password : $user->fecha_creacion);
        $dias = $user->dias_expiracion && !empty($user->dias_expiracion)? $user->dias_expiracion : config('auth.passwords.users.expire');
        
        if (Carbon::now()->diffInDays($fechapassword) > $dias) {
            return redirect()->route('password.expired',['company'=>request()->company]);
        }
        return $next($request);
    }
}