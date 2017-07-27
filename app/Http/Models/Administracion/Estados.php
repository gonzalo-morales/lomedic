<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelCompany;

class Estados extends ModelCompany
{
    // use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_estados';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_estado';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['estado', 'fk_id_pais', 'activo'];

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
		'estado' => 'required',
		'paises' => 'required',
		// 'activo' => 'required',
	];

	/**
	 * Un estado puede tener muchos municipios
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function municipios(){
		return $this->hasMany('App\Http\Models\Administracion\Municipios');
	}

	/**
	 * Un estado pertenece a un pais
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function paises(){
		return $this->belongsTo('App\Http\Models\Administracion\Paises', 'fk_id_pais');
	}

	/**
	 * @return field name of table
	 */
	public function getTable(){
	    return $this->table;
    }

}
