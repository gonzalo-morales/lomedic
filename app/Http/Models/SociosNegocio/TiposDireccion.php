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

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;
    
    protected $fields = [
        'tipo_direccion' => 'Tipo Direccion',
        'activo_text' => 'Estatus'
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];

    public function direcciones(){
        return $this->hasOne(DireccionesSociosNegocio::class);
    }
}
