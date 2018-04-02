<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use App\Http\Models\SociosNegocio\SociosNegocio;

class Diagnosticos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_diagnosticos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_diagnostico';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'clave_diagnostico',
		'fk_id_cliente',
		'diagnostico',
		'medicamento_sugerido',
		'activo'
	];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'clave_diagnostico' => 'required|max:10',
		'diagnostico' => 'required|max:200',
		'medicamento_sugerido' => 'required',
		'fk_id_cliente' => 'required'
	];

	protected $unique = ['clave_diagnostico','diagnostico'];

	/**
	 * Los atributos que seran visibles en smart-datatable
	 * @var array
	 */
	protected $fields = [
		'clave_diagnostico' => 'Clave',
		'diagnostico' => 'Diagnostico',
		'medicamento_sugerido' => 'Medicamento Sugerido'
	];

	public function socio_cliente()
	{
		return $this->hasOne(SociosNegocio::class, 'fk_id_cliente','id_diagnostico');
	}

}
