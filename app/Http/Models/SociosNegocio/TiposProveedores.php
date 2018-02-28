<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;

class TiposProveedores extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'maestro.sng_cat_tipos_proveedor';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_proveedor';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['tipo_proveedor', 'activo'];
    
    protected $fields = [
        'tipo_proveedor' => 'Tipo Proveedor',
        'activo_text' => 'Estatus'
    ];
   
    public function socionegocio(){
        return $this->belongsTo(SociosNegocio::class,'fk_id_tipo_socio');
    }
}