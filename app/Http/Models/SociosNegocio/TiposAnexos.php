<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;

class TiposAnexos extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'maestro.sng_cat_tipos_anexos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_anexo';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['tipo_anexo', 'activo'];
    
    protected $fields = [
        'tipo_anexo' => 'Tipo Anexo',
        'activo_text' => 'Estatus'
    ];

    public function anexos(){
        return $this->hasOne(AnexosSociosNegocio::class);
    }
}