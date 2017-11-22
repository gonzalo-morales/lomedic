<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;

class TiposSocioNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_cat_tipos_socio_negocio';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_socio';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['tipo_socio', 'activo'];

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

    public function socionegocio(){
        return $this->belongsTo(SociosNegocio::class,'fk_id_socio_negocio');
    }
}