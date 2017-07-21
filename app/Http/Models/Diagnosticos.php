<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Diagnosticos extends Model
{
	// use SoftDeletes;

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
	protected $fillable = ['clave_diagnostico', 'diagnostico', 'medicamento_sugerido',];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'clave_diagnostico' => 'required',
		'diagnostico' => 'required',
		'medicamento_sugerido' => 'required',
	];
}
