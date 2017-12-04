<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class UsosCfdis extends ModelBase
{
	protected $table = 'sat_cat_usos_cfdis';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_uso_cfdi';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['uso_cfdi','descripcion','activo'];//Solicitante devolución. false: localidad; true: proveedor;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'uso_cfdi' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/|Max:3',
		'descripcion' => 'required|max:255'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'uso_cfdi' => 'CFDI',
		'descripcion' => 'Descripción',
		'activo_span' => 'Estado'
	];

}
