<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ExpiredPasswordController extends Controller
{
    public $rules = ['actual'=>'required','password'=>'required|min:6','confirmar'=>'required|min:6'];
    public $nice = ['actual'=>'Contrase&ntilde;a actual','password'=>'Nueva contrase&ntilde;a','confirmar'=>'Confirmar contrase&ntilde;a'];
    
	public function expired($company)
	{
	    $user = Auth::user();
	    $fechapassword = new Carbon($user->fecha_cambio_password ? $user->fecha_cambio_password : $user->fecha_creacion);
	    $dias = $user->dias_expiracion && !empty($user->dias_expiracion)? $user->dias_expiracion : config('auth.passwords.users.expire');
	    
	    if (Carbon::now()->diffInDays($fechapassword) > $dias) {
	        $validator = \JsValidator::make($this->rules, [], $this->nice, '#form-reset');
	        
	        return view('auth.passwords.expired',['dias'=>$dias,'validator' => $validator]);
	    }
	    else
	        return redirect(companyAction('HomeController@index'));
	}
	
	public function reset(Request $request)
	{
	    $this->validate($request, $this->rules,[],$this->nice);
	    
	    // Checking current password
	    if (!Hash::check($request->actual, $request->user()->password)) {
	        return redirect()->back()->withErrors(['actual' => 'Contrase&ntilde;a actual no es correcta.']);
	    }
	    
	    // Checking confirm password
	    if ($request->password !== $request->confirmar) {
	        return redirect()->back()->withErrors(['confirmar' => 'Confirmacion de la contrase&ntilde;a no coincide.']);
	    }
	    
	    // Checking current and new is diferent password
	    if (Hash::check($request->password, $request->user()->password)) {
	        return redirect()->back()->withErrors(['password' => 'La nueva contrase&ntilde;a no pude ser igual a la actual.']);
	    }
	    
	    $request->user()->update([
	        'password' => bcrypt($request->password),
	        'fecha_cambio_password' => Carbon::now()->toDateTimeString()
	    ]);
	    return redirect()->back();
	}
}