<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class Subcategorias extends ModelBase
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
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	    'subcategoria' => 'Subcategoria',
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
		'subcategoria' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
	];


    public function acciones()
    {
        return $this->hasMany('App\Http\Models\Soporte\Acciones','fk_id_subcategoria');
    }
}
