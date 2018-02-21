<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Bancos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_bancos';

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
	  'nacional_text' => 'Nacional'
	];
	
	protected $unique = ['rfc','banco'];
	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'razon_social' => 'required|regex:/^[a-zA-Z\s]+/|max:200',
		'banco' => 'required|regex:/^[a-zA-Z\s]+/|max:25',
		'rfc' => 'required|max:13',
	];
	
	public function getNacionalTextAttribute()
	{
	    return isset($this->nacional) && $this->nacional === true ? 'Si' : 'No';
	}

	public function numeroscuenta()
	{
		return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
	}
}
