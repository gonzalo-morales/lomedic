<?php
namespace App\Http\Controllers\Auth;

use Session;
use Auth;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Administracion\Empresas;

class LoginController extends Controller
{
    /*
     |--------------------------------------------------------------------------
     | Login Controller
     |--------------------------------------------------------------------------
     |
     | This controller handles authenticating users for the application and
     | redirecting them to your home screen. The controller uses a trait
     | to conveniently provide its functionality to your applications.
     |
     */
    
    use AuthenticatesUsers;
    
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    
    public function __construct()
    {
        $this->middleware('guest',['except'=>'logout']);
    }
    
    protected function credentials(Request $request)
    {
        $field = filter_var($request->usuario, FILTER_VALIDATE_EMAIL) ? $this->username() : 'usuario';
        $request->session()->put('usuario', $request->{$field});
        
        $Usuario = Usuarios::where('usuario','=',$request->{$field})->get()->toarray();
        $idEmpresa = isset($Usuario[0]['fk_id_empresa_default']) ? $Usuario[0]['fk_id_empresa_default'] : 0;
        
        $QueryCompany =  Empresas::where('id_empresa','=',$idEmpresa)->get()->toarray();
        $redirect = isset($QueryCompany[0]['conexion']) ? $QueryCompany[0]['conexion'] : '/';
        
        $this->redirectTo = $redirect;
        
        return [$field => $request->{$field}, 'password' => $request->password, 'activo' => 1,'eliminar'=> 0];
    }
    
    protected function validateLogin(Request $request)
    {
        $field = filter_var($request->usuario, FILTER_VALIDATE_EMAIL) ? $this->username() : 'usuario';
        $this->validate($request, [$field => 'required', 'password' => 'required',]);
    }
}