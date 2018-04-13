<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;

class ConceptosViaje extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'adm_cat_conceptos_viaje';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_concepto';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['tipo_concepto', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'tipo_concepto'	=> 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:50'
	];

	protected $unique = ['tipo_concepto'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'tipo_concepto' => 'Nombre del concepto',
        'activo_span' => 'Estatus'
    ];
}