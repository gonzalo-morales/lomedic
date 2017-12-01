<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposEntrega extends ModelBase
{
	protected $table = 'adm_cat_tipos_entrega';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_tipo_entrega';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['tipo_entrega','activo'];//Solicitante devolución. false: localidad; true: proveedor;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'tipo_entrega' => 'required'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'tipo_entrega' => 'Entrega',
		'activo_span' => 'Estado'
	];
}