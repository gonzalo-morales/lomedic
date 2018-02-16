<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class SubgrupoProductos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_subgrupo_productos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_subgrupo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['subgrupo','fk_id_grupo','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'subgrupo' => 'max:100|regex:/^[a-zA-Z\s]+/|required',
		'fk_id_grupo' => 'required',
	];

	protected $unique = ['subgrupo'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
	    'subgrupo' => 'Subgrupo',
	    'grupo.grupo' => 'Grupo',
		'activo_span' => 'Estatus',
	];
	
	public function grupo()
	{
	    return $this->belongsTo(GrupoProductos::class,'fk_id_grupo','id_grupo');
	}
}