<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class UnidadesMedicas extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_unidades_medicas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_unidad_medica';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'nombre' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'nombre' => 'Descripcion',
		'activo_span' => 'Estatus'
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
