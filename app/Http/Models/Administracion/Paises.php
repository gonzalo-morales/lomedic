<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Paises extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_paises';

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
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'pais'		=> 'required|max:150|regex:/^[a-zA-Z\s]+/',
	];

    protected $unique = ['pais'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'pais' => 'Pais',
		'activo_span' => 'Estatus'
	];

	/**
	 * Obtenemos estados relacionados
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function estados() {
		return $this->hasMany(Estados::class, 'fk_id_pais', 'id_pais');
	}

}