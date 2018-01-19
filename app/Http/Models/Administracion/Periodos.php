<?php
namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Periodos extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_periodos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_periodo';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['periodo','activo'];

	/**
	 * Indicates if the model should be timestamped.
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [];

	/**
	 * Los atributos que seran visibles en smart-datatable
	 * @var array
	 */
	protected $fields = [
		'periodo' => 'Periodo',
	    'activo_span' => 'Estatus'
	];
}