<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_empresas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_empresa';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	// protected $fillable = ['razon_social', 'banco', 'rfc', 'nacional'];

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
	// public $rules = [
	//     'razon_social' => 'required',
	//     'banco' => 'required',
	// ];

	/**
	 * Los modulos que relacionan a la empresa.
	 */
	public function modulos()
	{
		return $this->belongsToMany('App\Http\Models\Modulos', 'ges_det_modulo_empresa', 'fk_id_empresa', 'fk_id_modulo');
	}


}
