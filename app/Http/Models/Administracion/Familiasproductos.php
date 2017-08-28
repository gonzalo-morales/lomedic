<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Familiasproductos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_familias_productos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_familia';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['descripcion', 'tipo_presentacion','nomenclatura','tipo','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'descripcion' => 'required',
		'tipo_presentacion' => 'required',
		'nomenclatura' => 'required',
		'tipo' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'descripcion' => 'Familia',
		'tipo_presentacion' => 'Presentación',
		'tipo' => 'Tipo',
		'nomenclatura' => 'Nomenclatura',
		'activo_span' => 'Estatus',
	];

	public function usuario()
	{
		$this->hasOne('app\Http\Models\Administracion\Usuarios');
	}

	public function empresa()
	{
		$this->$this->hasOne('app\Http\Models\Administracion\Empresas');
	}
}
