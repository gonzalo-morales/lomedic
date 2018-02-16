<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;

class TiposDireccion extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_cat_tipos_direccion';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_direccion';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['tipo_direccion', 'activo'];
    
    protected $fields = [
        'tipo_direccion' => 'Tipo Direccion',
        'activo_text' => 'Estatus'
    ];

    public function direcciones(){
        return $this->hasOne(DireccionesSociosNegocio::class);
    }
}
