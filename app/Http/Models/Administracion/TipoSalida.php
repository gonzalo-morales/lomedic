<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TipoSalida extends ModelBase
{
    protected $table = 'gen_cat_tipo_salida';

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
    protected $fillable = ['salida','activo'];

    protected $unique = ['salida'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'salida' => 'required'
    ];
}
