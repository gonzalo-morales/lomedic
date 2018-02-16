<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class Categorias extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'sop_cat_categorias';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_categoria';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['categoria', 'activo'];
	
	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	    'categoria' => 'Categoria',
	    'activo_span' => 'Estatus'
	];

    protected $unique = ['categoria'];

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
		'categoria' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:255',
	];

	public function solicitudes()
    {
        return $this->belongsToMany('App\Http\Models\Soporte\Solicitudes');
    }

    public function subcategorias()
    {
            return $this->hasMany('App\Http\Models\Soporte\Subcategorias','fk_id_categoria');
    }
}
