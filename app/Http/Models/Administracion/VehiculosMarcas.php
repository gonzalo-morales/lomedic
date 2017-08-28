<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class VehiculosMarcas extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_vehiculos_marcas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_marca';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['marca', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'marca' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'marca' => 'Marca',
		'activo_span' => 'Activo'
	];

	public function modelos()
	{
		return $this->hasMany('App\Http\Models\Administracion\VehiculosModelos');
	}
}