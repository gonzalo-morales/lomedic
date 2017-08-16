<?php

namespace App\Http\Models\RecursosHumanos;

use App\Http\Models\ModelBase;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Collection;

class Puestos extends ModelBase
{
    use Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_cat_puestos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_puesto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['descripcion','activo'];
    
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'descripcion' => 'Puesto',
        'activo' => 'Activo'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'descripcion' => 'required',

    ];

    public function usuario()
    {
        $this->hasOne('app\Http\Models\Administracion\Usuarios');
    }

    public function empresa()
    {
        $this->$this->hasOne('app\Http\Models\Administracion\Empresas');
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

    /**
     * Obtenemos permisos asignados al usuario
     * @return array
     */
    public function permisos()
    {
        $permisos = new Collection();
        # Obtenemos permisos relacionados a perfiles del usuario
        foreach ($this->perfiles as $perfil) {
            $permisos = $permisos->merge( $perfil->permisos);
        }
        # Anexamos permisos relacionados al usuario
        return $permisos->merge($this->belongsToMany(Permisos::class, 'ges_det_permisos_usuarios', 'fk_id_usuario', 'fk_id_permiso')->getResults());
    }

    /**
     * Obtenemos modulos a los que tiene acceso el usuario por medio de sus permisos
     * @return array
     */
    public function modulos()
    {
        # Obtenemos modulos en base a ...
        return Modulos::whereHas('permisos', function($q) {
            # Modulos relacionados a empresa actual
            $q->whereIn('id_modulo', Empresas::where('conexion', request()->company)->first()->modulos->pluck('id_modulo') );
            # Modulos relacionados a los permisos del usuario
            $q->whereIn('id_permiso', $this->permisos()->pluck('id_permiso') );
        })->get();
    }

}
