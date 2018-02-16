<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Perfiles extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adm_cat_perfiles';

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
    protected $fillable = ['nombre_perfil', 'descripcion', 'activo'];

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
        return $this->belongsToMany(Usuarios::class, 'ges_det_perfiles_usuarios', 'fk_id_perfil', 'fk_id_usuario');
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
        return $this->hasMany('adm_det_permisos_perfiles');
    }

}