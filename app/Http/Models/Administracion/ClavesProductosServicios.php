<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class ClavesProductosServicios extends ModelBase
{
	protected $table = 'sat_cat_claves_productos_servicios';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_clave_producto_servicio';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['clave_producto_servicio','descripcion','vigencia','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'clave_producto_servicio' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:255',
		'descripcion' => 'required|max:255',
		'vigencia' => 'nullable|date'
	];

	protected $unique = ['clave_producto_servicio'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'clave_producto_servicio' => 'Clave',
		'descripcion' => 'Descripcion',
		'vigencia' => 'Vigencia',
		'activo_span' => 'Estatus'
	];

}
