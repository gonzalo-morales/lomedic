<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;

class AnexosSociosNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_det_anexos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_anexo';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fk_id_socio_negocio', 'fk_id_tipo_anexo', 'nombre', 'archivo', 'activo'];

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

    public function sociosnegocio(){
        return $this->belongsTo(SociosNegocio::class,'fk_id_socio_negocio');
    }

    public function tipoanexo(){
        return $this->belongsTo(TiposAnexos::class,'fk_id_tipo_anexo');
    }
}