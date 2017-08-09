<?php

namespace App\Http\Models\Soporte;

use Illuminate\Database\Eloquent\Model;

class Acciones extends Model
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
		'accion' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
        'fk_id_subcategoria' => 'required',
	];

}
