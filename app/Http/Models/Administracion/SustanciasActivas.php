<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class SustanciasActivas extends ModelBase
{
	protected $table = 'gen_cat_sustancias_activas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_sustancia_activa';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['sustancia_activa', 'opcion_gramaje','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'sustancia_activa' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'sustancia_activa' => 'Sustancia Activa',
		'gramaje_text' => 'Gramaje'
	];

	/**
	 * Accesor
	 * @return string
	 */
	public function getGramajeTextAttribute()
	{
		return $this->opcion_gramaje ? 'Si' : 'No';
	}
}