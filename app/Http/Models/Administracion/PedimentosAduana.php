<?php
namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class PedimentosAduana extends ModelBase
{
	protected $table = 'sat_cat_pedimentos_aduana';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_pedimento';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['aduana','patente','ejercicio','cantidad','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'aduana' => 'required|max:2|numeric',
		'patente' => 'required|max:4|numeric',
		'ejercicio' => 'required|max:4|numeric',
		'cantidad' => 'required|max:255|numeric'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'aduana' => 'No. Aduana',
		'patente' => 'Patente',
		'ejercicio' => 'Ejercicio',
		'cantidad' => 'Cantidad',
		'activo_span' => 'Estatus'
	];
}