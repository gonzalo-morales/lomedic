<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class Acciones extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'sop_cat_acciones';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_accion';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['accion','fk_id_subcategoria', 'activo'];
	
	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	    'accion' => 'Accion',
	    'activo_span' => 'Estatus'
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
		'accion' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
        'fk_id_subcategoria' => 'required',
	];

}
