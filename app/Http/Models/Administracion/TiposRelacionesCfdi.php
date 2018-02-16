<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposRelacionesCfdi extends ModelBase
{
	protected $table = 'maestro.sat_tipos_relaciones';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_sat_tipo_relacion';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['tipo_relacion','descripcion','factura','nota_credito','nota_cargo','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [];

    protected $unique = ['tipo_relacion'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'tipo_relacion' => 'Tipo Relacion',
		'descripcion' => 'Descripción',
		'activo_span' => 'Estatus'
	];

}
