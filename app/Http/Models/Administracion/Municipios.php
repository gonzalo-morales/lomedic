<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelCompany;

class Municipios extends ModelCompany
{
    // use SoftDeletes;

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
	 * The attribute activo
	 * @var boolean
	 */
	// protected $activo = 'activo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['municipio', 'fk_id_estado', 'activo'];

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
		'municipio'     => 'required',
		'fk_id_estado'	=> 'required',
		// 'activo'		=> 'required',
	];


	/**
	 * Un municipio pertenece a un estado
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function estado(){
		return $this->belongsTo('App\Http\Models\Administracion\Estados','fk_id_estado');
	}

	/**
	 * @return field name of table
	 */
	public function getTable(){
	    return $this->table;
    }

}