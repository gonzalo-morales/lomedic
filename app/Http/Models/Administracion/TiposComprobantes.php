<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposComprobantes extends ModelBase
{
    protected $table = 'sat_cat_tipos_comprobantes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_comprobante';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tipo_comprobante','descripcion','limite','activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'tipo_comprobante' => 'required|max:10',
        'descripcion' => 'required|max:255',
        'limite' => 'required|numeric'
    ];

    protected $unique = ['tipo_comprobante'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'tipo_comprobante' => 'Tipo Comprobante',
        'descripcion' => 'Descripción',
        'limite' => 'Límite',
        'activo_span' => 'Estatus'
    ];

    public $niceNames = [
        'tipo_comprobante' => 'Tipo Comprobante',
        'descripcion' => 'Descripción',
        'limite' => 'Límite',
    ];
}