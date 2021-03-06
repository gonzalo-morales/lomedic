<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;


class Monedas extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sat_cat_monedas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_moneda';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['moneda', 'descripcion','total_decimales','porcentaje_variacion','activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'moneda'   => 'required|max:3',
        'descripcion'   => 'required|max:255|regex:/^[a-zA-Z\s]+/',
        'total_decimales'   => 'required|integer|max:10'
    ];

    protected $unique = ['moneda'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'moneda' => 'Abreviatura Moneda',
        'descripcion' => 'Descripción',
        'total_decimales' => 'Cantidad Decimales',
        'activo_span' => 'Estatus'
    ];
}