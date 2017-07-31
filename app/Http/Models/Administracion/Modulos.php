<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Modulos extends Model
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ges_cat_modulos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_modulo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'descripcion', 'url', 'icono', 'accion_menu', 'accion_barra', 'accion_tabla', 'modulo_seguro','activo'];

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
	public function rules($id_modulo = false) {
		return [
			'nombre' => 'required|unique:ges_cat_modulos' . ($id_modulo ? ",nombre,$id_modulo,id_modulo" : ''),
			'descripcion' => 'required',
			'url' => 'required',
			'empresas' => 'required',
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

	/**
	 * Obtenemos los modulos hijos
	 * @return array
	 */
	public function  modulos()
	{
		return $this->belongsToMany(Modulos::class, 'ges_det_modulos', 'fk_id_modulo_hijo','fk_id_modulo');
	}

	/**
	 * Obtenemos los permisos relacionados al modulo
	 * @return array
	 */
	public function permisos()
	{
		return $this->belongsToMany(Permisos::class, 'ges_det_permisos_modulos', 'fk_id_modulo', 'fk_id_permiso');
	}

}
