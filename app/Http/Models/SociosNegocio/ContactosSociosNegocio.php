<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;

class ContactosSociosNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_det_contactos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_contacto';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fk_id_socio_negocio', 'fk_id_tipo_contacto', 'nombre', 'puesto', 'correo', 'telefono_oficina', 'extension_oficina', 'celular', 'activo'];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];

    public function sociosNegocio(){
        return $this->belongsTo(SociosNegocio::class,'fk_id_socio_negocio');
    }

    public function tipocontacto(){
        return $this->belongsTo(TiposContacto::class,'fk_id_tipo_contacto');
    }
}