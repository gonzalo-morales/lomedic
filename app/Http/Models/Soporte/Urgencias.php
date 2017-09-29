<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class Urgencias extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'sop_cat_urgencias';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_urgencia';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['urgencia', 'activo'];
	
	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	    'urgencia' => 'Urgencia',
	    'activo_span' => 'Activo'
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
		'urgencia' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
	];

    public function solicitud()
    {
        return $this->belongsToMany('App\Http\Models\Soporte\Solicitudes');
    }
}
