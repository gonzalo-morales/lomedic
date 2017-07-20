<?php
namespace App\Http\Controllers\Auth;

use Session;
use Auth;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
        Session::flush();
        
        $request->session()->put('usuario', $request->get('email'));
        $request->session()->put('id_sistema', $request->get('sistema'));
        
        $idEmpresa = !empty($request->get('sistema')) ? $request->get('sistema') : 0;
        
        $QueryCompany =  DB::table('gen_cat_empresas')->select('conexion')->where('id_empresa','=',$idEmpresa)->get()->toarray();
        $redirect = isset($QueryCompany[0]->conexion) ? $QueryCompany[0]->conexion.'/' : '/';
        
        $this->redirectTo = $redirect;
        
        
        $field = filter_var($request->get($this->username()), FILTER_VALIDATE_EMAIL) ? $this->username() : 'usuario';
        
        return [$field => $request->get($this->username()),'password' => $request->password, 'activo' => 1,'eliminar'=> 0];
    }
    
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [$this->username() => 'required', 'password' => 'required', 'sistema' => 'required',]);
    }
}