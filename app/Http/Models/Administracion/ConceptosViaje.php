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
		'tipo_concepto'	=> 'required|max:50'
	];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'tipo_concepto' => 'Metodo de pago',
        'activo_span' => 'Activo'
    ];
}
