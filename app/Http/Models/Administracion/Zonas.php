<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Zonas extends ModelBase
{
    protected $table = 'gen_cat_zonas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_zona';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['zona','activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'zona' => 'required'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'zona' => 'Zona',
        'activo_span' => 'Estado'
    ];

}

