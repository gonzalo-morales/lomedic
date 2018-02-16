<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\Compras\CondicionesAutorizacion;
use App\Http\Models\ModelBase;
use Illuminate\Support\Collection;
use Illuminate\Auth\Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use App\Notifications\MyResetPassword;
use App\Http\Models\RecursosHumanos\Empleados;

class Usuarios extends ModelBase implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Notifiable, Authenticatable, Authorizable, CanResetPassword;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.adm_cat_usuarios';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'usuario', 'nombre_corto','activo','password','activo','fk_id_empresa_default','fecha_cambio_password','dias_expiracion'
    ];

    // protected $unique = ['usuario'];

    protected $fields = [
        'id_usuario' => '#',
        'usuario' => 'Usuario',
        'nombre_corto' => 'Nombre',
        'fecha_creacion' => 'Fecha Creacion'
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    public $rules = [
        'nombre_corto' => 'required|max:100',
        'usuario' => 'required|max:20',
        'password' => 'required|max:60',
        'fk_id_empresa_default' => 'required',
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MyResetPassword($token));
    }

    public function mails(){
        return $this->hasMany('app\Http\Models\Correos');
    }

    public function branches(){
        return $this->belongsToMany('app\Http\Models\Sucursales','ges_det_usuario_sucursal','fk_id_usuario','fk_id_sucursal');
    }

    /**
     * Obtenemos perfiles asignados al usuario
     * @return array
     */
    public function perfiles(){
        return $this->belongsToMany(Perfiles::class, 'ges_det_perfiles_usuarios', 'fk_id_usuario', 'fk_id_perfil');
    }

    public function permisos()
    {
        return $this->belongsToMany(Permisos::class, 'ges_det_permisos_usuarios', 'fk_id_usuario', 'fk_id_permiso');
    }
    
    public function empleado(){
        return $this->belongsTo(Empleados::class, 'fk_id_empleado', 'id_empleado');
    }

    /**
     * Obtenemos permisos asignados al usuario
     * @return array
     */
    public function checkAuthorization($routeaction = Null)
    {
        $allpermisos = new Collection();

        foreach ($this->perfiles as $perfil) {
            $permisoperfil = empty($routeaction) ? $perfil->permisos : $perfil->permisos->where('descripcion',$routeaction);

            if($allpermisos->isEmpty())
                $allpermisos = $permisoperfil;
            else
                $allpermisos->merge($permisoperfil);
        }

        $permisousuario = empty($routeaction) ? $this->permisos : $this->permisos->where('descripcion',$routeaction);

        if($allpermisos->isEmpty())
            $allpermisos = $permisousuario;
        else
            $allpermisos->merge($permisousuario);

        return $allpermisos->pluck('descripcion','id_permiso')->contains($routeaction);
    }

    public function getpermisos()
    {
        $permisos = new Collection();
        $perfiles = $this->perfiles;
        # Obtenemos permisos relacionados a perfiles del usuario
        foreach ($perfiles as $perfil) {
            $permisos = $permisos->merge( $perfil->permisos);
        }

        # Anexamos permisos relacionados al usuario
        return $permisos->merge($this->belongsToMany(Permisos::class, 'ges_det_permisos_usuarios', 'fk_id_usuario', 'fk_id_permiso')->getResults());
    }

    /**
     * Obtenemos modulos anidados a los que tiene acceso el usuario por medio de sus permisos
     * @param  Empresa $empresa
     * @return array
     */
    public $modulos_menu = null;

    public function modulos_anidados($empresa = null, $idmenu = null)
    {
        $empresa = $empresa ?: Empresas::where('conexion', request()->company)->first();
        $this->modulos_menu = $this->modulos_menu ?: $this->getmenu($empresa);

        $menu = $this->modulos_menu->where('fk_id_modulo_hijo','=',$idmenu);

        foreach ($menu as $key=>$itemMenu) {
            $menu[$key]->submodulos = $this->modulos_anidados($empresa,$itemMenu->id_modulo);
        }
        return $menu;
    }

    public function getmenu($empresa = null)
    {
        $empresa = $empresa ?: Empresas::where('conexion', request()->company)->first();
        $id_empresa = isset($empresa->id_empresa) ? $empresa->id_empresa : 0;

        $modulos = Modulos::where('activo',1)->where('accion_menu','=',1)
        ->wherein('id_modulo',$this->getpermisos()->pluck('id_permiso'))
        ->leftJoin('ges_det_modulos','fk_id_modulo','id_modulo')
        ->where('fk_id_empresa', '=', $id_empresa)
        ->orderBy('orden')->orderBy('nombre');

        return $modulos->get();
    }

    public function autorizacionessolicitudes()
    {
        return $this->belongsToMany(DetalleCondicionesAutorizaciones::class,'com_det_usuarios_autorizados','fk_id_usuario','id_usuario');
        // return $this->belongsToMany(DetalleCondicionesAutorizaciones::class,'com_det_autorizaciones_usuarios','fk_id_usuario','id_usuario');
    }

    public function condiciones()
    {
        return $this->belongsToMany(CondicionesAutorizacion::class,'com_det_usuarios_autorizados','fk_id_usuario','fk_id_condicion');
    }
}
