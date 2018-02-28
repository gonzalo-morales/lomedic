<?php

namespace App\Http\Models\Administracion;

use Illuminate\Support\Collection;
use App\Http\Models\ModelBase;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Administracion\Permisos;

class Perfiles extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.adm_cat_perfiles';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_perfil';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre_perfil',
        'descripcion', 
        'activo'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'nombre_perfil' => 'Nombre del perfil',
        'descripcion' => 'Descripción',
        'activo_span' => 'Estatus'
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'nombre_perfil' => 'required|max:20|regex:/^[a-zA-Z\s]+/',
    ];

    protected $unique = ['nombre_perfil'];

    /**
     * Obtenemos usuarios relacionados al perfil
     * @return array
     */
    public function usuarios(){
        return $this->belongsToMany(Usuarios::class, 'adm_det_perfiles_usuarios', 'fk_id_perfil', 'fk_id_usuario');
    }

    /**
     * Obtenemos permisos asignados al perfil
     * @return array
     */
    public function permisos()
    {
        return $this->belongsToMany(Permisos::class, 'ges_det_permisos_perfiles', 'fk_id_perfil', 'fk_id_permiso');
    }

    public function permisos_perfiles()
    {
        return $this->hasMany('adm_det_permisos_perfiles','fk_id_perfil','fk_id_modulo_accion');
    }

}