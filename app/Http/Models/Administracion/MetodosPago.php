<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class MetodosPago extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'sat_cat_metodos_pago';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_metodo_pago';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['metodo_pago', 'descripcion', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'metodo_pago'	=> 'required|max:3',
		'descripcion'	=> 'required|max:255',
	];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'metodo_pago' => 'Metodo de pago',
        'descripcion' => 'Descripción',
        'activo_span' => 'Estatus',
    ];
}