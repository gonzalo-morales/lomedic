<?php

namespace App\Http\Models\Soporte;

use Illuminate\Database\Eloquent\Model;

class Subcategorias extends Model
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'sop_cat_subcategorias';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_subcategoria';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['subcategoria', 'fk_id_categoria','activo'];

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
		'subcategoria' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
	];


    public function acciones()
    {
        return $this->hasMany('App\Http\Models\Soporte\Acciones','fk_id_subcategoria');
    }
}
