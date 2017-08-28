<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;

class GrupoProductos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_grupo_productos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_grupo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['descripcion','descripcion_producto','nomenclatura','tipo','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'descripcion' => 'required',
		'descripcion_producto' => 'required',
		'nomenclatura' => 'required',
		'tipo' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'descripcion' => 'Descripción',
		'estatus' => 'Estatus',
		'descripcion_producto' => 'Descripción producto',
		'nomenclatura' => 'Nomenclatura',
		'tipo' => 'Tipo',
		'activo_span' => 'Activo',
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
