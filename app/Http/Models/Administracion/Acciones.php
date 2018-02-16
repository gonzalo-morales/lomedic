<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Acciones extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adm_cat_acciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_accion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre','accion','activo'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $unique = ['accion'];

    /**
     * The validation rules
     * @var array
     */
    public function rules($id_modulo = false) {
        return [
            'nombre' => 'required',
            'accion' => 'required',
            'activo' => 'required',
        ];
    }

    /**
     * Obtenemos las empresas relacionadas al modulo
     * @return array
     */
    public function empresas()
    {
        return $this->belongsToMany(Empresas::class, 'ges_det_modulos', 'fk_id_modulo', 'fk_id_empresa');
    }

    public function modulo_accion()
    {
        return $this->belongsToMany(Modulos::class, 'adm_det_modulo_accion', 'fk_id_accion', 'fk_id_modulo');
    }

    /**
     * Obtenemos los modulos hijos
     * @return array
     */
    public function  modulos()
    {
        return $this->belongsToMany(Modulos::class, 'adm_det_modulos', 'fk_id_modulo_hijo','fk_id_modulo');
    }

    /**
     * Obtenemos los permisos relacionados al modulo
     * @return array
     */
    public function permisos()
    {
        return $this->belongsToMany(Permisos::class, 'ges_det_permisos_modulos', 'fk_id_modulo', 'fk_id_permiso');
    }

    public function modulos_acciones()
    {
        return $this->belongsTo('App\Http\Models\Administracion\ModulosAcciones','id_accion','fk_id_accion');
    }

}