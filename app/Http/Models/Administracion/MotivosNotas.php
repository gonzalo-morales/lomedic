<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class MotivosNotas extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_motivos_notas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_motivo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['motivo', 'tipo', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'motivo'	=> 'required|max:50|regex:/^[a-zA-Z\s]+/',
		'tipo'	=> 'required',
	];

    protected $unique = ['motivo'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'motivo' => 'Descripción',
		'tipo_formated' => 'Tipo',
		'activo_span' => 'Estatus',
	];

	public function getTipoFormatedAttribute()
	{
		return $this->tipo == 1 ? 'Cuentas por Pagar':'Cuentas por Cobrar';
	}
}
