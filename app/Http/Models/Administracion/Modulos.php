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
	 * Las empresas que relacionan al modulo.
	 */
	public function empresas()
	{
		return $this->belongsToMany('App\Http\Models\Administracion\Empresas', 'ges_det_modulo_empresa', 'fk_id_modulo', 'fk_id_empresa');
	}

	public function perfiles()
    {
        return $this->belongsToMany('App\Http\Models\Administracion\Perfiles','ges_det_modulo_perfil','fk_id_modulo','fk_id_perfil');
    }

    public function  modulos()
    {
		return $this->belongsToMany('App\Http\Models\Administracion\Modulos', 'ges_det_parents', 'fk_id_modulo','fk_id_parent');
    }
}
