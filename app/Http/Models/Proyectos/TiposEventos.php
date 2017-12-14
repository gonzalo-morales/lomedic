<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelBase;

class TiposEventos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'pry_cat_tipo_evento';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_clasificacion_proyecto';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['clasificacion', 'nomenclatura','activo','fk_id_tipo_proyecto'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
        'clasificacion' => 'required',
        'nomenclatura' => 'required|alpha',
        'fk_id_tipo_proyecto' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
        'clasificacion' => 'Aplicación',
        'nomenclatura' => 'Nomenclatura',
        'tipoProyecto.tipo_proyecto' => 'Tipo de proyecto',
        'activo_span' => 'Estatus'
	];

	public $niceNames = [
        'fk_id_tipo_proyecto' => 'tipo proyecto'
    ];

	function tipoProyecto()
    {
        return $this->hasOne(TiposProyectos::class,'id_tipo_proyecto','fk_id_tipo_proyecto');
    }
}
