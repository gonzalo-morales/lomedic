<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class UsosCfdis extends ModelBase
{
	protected $table = 'maestro.sat_cat_usos_cfdis';

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
	protected $fillable = ['uso_cfdi','descripcion','activo'];//Solicitante devoluciÃ³n. false: localidad; true: proveedor;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'uso_cfdi' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|Max:3',
		'descripcion' => 'required|max:255'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'uso_cfdi' => 'CFDI',
		'descripcion' => 'DescripciÃ³n',
		'activo_span' => 'Estatus'
	];

}
