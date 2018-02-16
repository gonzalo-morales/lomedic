<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Impuestos extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_impuestos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_impuesto';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['impuesto','porcentaje','activo','tasa_o_cuota','tipo_factor','Descripcion','numero_impuesto'];
    
    protected $unique = ['impuesto'];

    protected $fields = [
        'impuesto' => 'Impuesto',
        'porcentaje' => 'Porcentaje',
        'activo_span' => 'Estatus',
    ];

    public $rules = [
        'porcentaje'=> 'numeric',
        'impuesto' => 'required'
    ];

    public function getFields()
    {
        return $this->fields;
    }

}
