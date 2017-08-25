<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;

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
		'motivo'	=> 'required',
		'tipo'	=> 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'motivo' => 'Laboratorio',
		'tipo_formated' => 'Tipo',
		'activo_span' => 'Activo',
	];

	public function getTipoFormatedAttribute()
	{
		return $this->tipo == 1 ? 'Cuentas por Pagar':'Cuentas por Cobrar';
	}

	public function getActivoFormatedAttribute()
	{
		return $this->activo ? 'Activo' : 'Inactivo';
	}

	public function getActivoSpanAttribute()
	{
		return new HtmlString("<span class=" . ($this->activo ? 'toast_success' : 'toast_error') . ">&nbsp;$this->activo_formated&nbsp;</span>");
	}

}
