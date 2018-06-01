<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class PresentacionVenta extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_presentacion_venta';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_presentacion_venta';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['presentacion_venta', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'presentacion_venta' => 'required|max:150|regex:/^[a-zA-Z\s]+/'
	];

    protected $unique = ['presentacion_venta'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'presentacion_venta' => 'Presentación Venta',
		'activo_span' => 'Estatus',
	];
}
