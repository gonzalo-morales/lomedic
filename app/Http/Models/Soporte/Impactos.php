<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class Impactos extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.sop_cat_impactos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_impacto';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['impacto', 'activo'];
	
	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	    'impacto' => 'Impacto',
	    'activo_span' => 'Estatus'
	];

    protected $unique = ['impacto'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'impacto' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:255',
	];

	public function solicitud()
    {
        return $this->belongsToMany('App\Http\Models\Soporte\Solicitudes');
    }
}
