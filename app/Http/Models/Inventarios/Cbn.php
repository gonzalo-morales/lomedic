<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;

class Cbn extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.inv_cat_cbn';
    
    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_cbn';
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['clave_cbn','descripcion','precio_comision','precio_causes','precio_imss','vigencia','activo'];
    
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'clave_cbn' => 'Clave',
        'descripcion' => 'Descripcion',
        'precio_comision' => 'Precio Comision',
        'precio_causes' => 'Precio Causes',
        'precio_imss' => 'Precio Imss',
        'activo_span'=> 'Estatus',
    ];
    
    /**
     * The validation rules
     * @var array
     */
    public $rules = [];
}
