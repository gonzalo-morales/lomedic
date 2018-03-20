<?php

namespace App\Http\Models\Administracion;

use Illuminate\Support\Collection;
use App\Http\Models\ModelBase;

class Modulos extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ges_cat_modulos';
//    protected $table = 'adm_cat_modulos';
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
	protected $fillable = [
		'nombre',
		'descripcion',
		'url',
		'icono',
		'accion_menu',
		'accion_barra',
		'accion_tabla',
		'modulo_seguro',
		'activo'
	];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'nombre' => 'Nombre del perfil',
        'descripcion' => 'Descripción',
        'activo_span' => 'Estatus'
    ];

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

	#	MEN'S STUFF
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


	#	NANDO'S STUFF (adm)
	
    public function modulos_acciones()
    {
        return $this->belongsToMany(Acciones::class,'adm_det_modulo_accion','fk_id_modulo','fk_id_accion');
	}
}