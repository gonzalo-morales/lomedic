<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Municipios extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_municipios';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_municipio';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['municipio', 'fk_id_estado', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'municipio'     => 'required|max:100|regex:/^[a-zA-Z\s]+/',
		'fk_id_estado'	=> 'required',
	];

    protected $unique = ['municipio'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'municipio' => 'Municipio',
		'estado.estado' => 'Estado',
		'activo_span' => 'Estatus',
	];

	/**
	 * Obtenemos estado relacionado
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function estado(){
		return $this->belongsTo(Estados::class, 'fk_id_estado', 'id_estado')->select(['id_estado', 'estado']);
	}
}
