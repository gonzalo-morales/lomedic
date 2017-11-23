<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;

class RamosSocioNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_cat_ramos_socio';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_ramo';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['ramo', 'activo'];

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