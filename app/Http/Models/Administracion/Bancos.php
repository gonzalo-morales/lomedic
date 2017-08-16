<?php

namespace App\Http\Models\Administracion;

use DB;
use App\Http\Models\ModelBase;

class Bancos extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_bancos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_banco';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['razon_social', 'banco', 'rfc', 'nacional'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	  'banco' => 'Banco',
	  'rfc' => 'RFC',
	  'razon_social' => 'Razón Social',
	  'nacional' => 'Nacional'
	];

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
		'razon_social' => 'required',
		'banco' => 'required',
	];

	public function numeroscuenta()
	{
		return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
	}

}
