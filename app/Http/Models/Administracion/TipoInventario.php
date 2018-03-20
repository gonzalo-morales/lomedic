<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TipoInventario extends ModelBase
{
    protected $table = 'gen_cat_tipo_inventario';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tipo','activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'tipo' => 'required|max:30|regex:/^[a-zA-Z\s]+/'
    ];

    protected $unique = ['tipo'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'tipo' => 'Tipo de Inventario',
        'activo_span' => 'Estatus'
    ];

    public $niceNames = [
        'tipo' => 'Tipo de Inventario',
    ];
}