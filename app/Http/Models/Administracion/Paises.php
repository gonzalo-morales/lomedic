<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Paises extends Model
{
    // use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_paises';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_pais';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['pais', 'activo'];

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
		'pais'		=> 'required',
		// 'activo'	=> 'required',
	];

}
