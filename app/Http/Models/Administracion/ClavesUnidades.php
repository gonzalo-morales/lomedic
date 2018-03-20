<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class ClavesUnidades extends ModelBase
{
	protected $table = 'sat_cat_claves_unidades';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_clave_unidad';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['clave_unidad','descripcion','activo'];//Solicitante devoluciÃ³n. false: localidad; true: proveedor;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'clave_unidad' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:255',
		'descripcion' => 'required|max:255',
	];

	protected $unique = ['clave_unidad'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'clave_unidad' => 'Clave',
		'descripcion' => 'Descripcion',
		'activo_span' => 'Estatus'
	];
}
