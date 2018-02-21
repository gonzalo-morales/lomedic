<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TipoSucursal extends ModelBase
{
	protected $table = 'gen_cat_tipo_sucursal';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_tipo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['tipo','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'tipo' => 'required|max:30|regex:/^[a-zA-Z\s]+/'
	];

    protected $unique = ['tipo'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'tipo' => 'Tipo de Sucursal',
		'activo_span' => 'Estatus'
	];

	public $niceNames = [
		'tipo' => 'Tipo de Sucursal',
	];
}
