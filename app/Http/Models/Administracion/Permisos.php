<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permisos extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ges_cat_permisos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_permiso';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['descripcion'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'descripcion' => 'required',
    ];

    /**
     * Obtenemos los modulos relacionados a permiso
     * @return array
     */
    public function modulos()
    {
        return $this->belongsToMany(Modulos::class, 'ges_det_permisos_modulos', 'fk_id_permiso', 'fk_id_modulo');
    }
    
    public function perfiles()
    {
        return $this->belongsToMany(Perfiles::class, 'ges_det_permisos_perfiles', 'fk_id_permiso', 'fk_id_perfil');
    }
    
    public function usuarios()
    {
        return $this->belongsToMany(Usuarios::class, 'ges_det_permisos_usuarios', 'fk_id_permiso', 'fk_id_usuario');
    }

}