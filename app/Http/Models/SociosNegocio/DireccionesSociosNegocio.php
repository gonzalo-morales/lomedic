<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Municipios;

class DireccionesSociosNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_det_direcciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_direccion';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fk_id_socio_negocio','fk_id_pais','fk_id_estado','fk_id_municipio','fk_id_colonia','fk_id_tipo_direccion','calle','num_exterior','num_interior','cp','activo'];
    
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
    public function tipoDireccion(){
        return $this->belongsTo(TiposDireccion::class,'fk_id_tipo_direccion');
    }
    public function pais(){
        return $this->belongsTo(Paises::class,'fk_id_pais');
    }
    public function estado(){
        return $this->belongsTo(Estados::class,'fk_id_estado');
    }
    public function municipio(){
        return $this->belongsTo(Municipios::class,'fk_id_municipio');
    }
}