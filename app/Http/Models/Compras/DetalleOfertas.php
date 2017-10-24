<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\ModelCompany;
use DB;

class DetalleOfertas extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_det_ofertas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_oferta_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_oferta','fk_id_sku','fk_id_upc','fk_id_cliente','cantidad','fk_id_unidad_medida',
        'fk_id_impuesto','precio_unitario','total','fk_id_proyecto','descuento_detalle','fk_id_solicitud'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
//        'fk_id_sku' => 'required',
//        'fk_id_upc' => 'required',
//        'cantidad' => 'required|digits_between:1,9999',
//        'fk_id_unidad_medida' => 'required',
//        'fk_id_impuesto' => 'required',
//        'precio_unitario' => 'required|regex:/^\d{0,6}(\.\d{0,2})?$/',
//        'descuento_detalle' => 'regex:/^\d{0,3}(\.\d{0,4})?$/'
    ];

    public $niceNames = [
        'fk_id_sku' => 'sku',
        'fk_id_upc' => 'upc',
        'fk_id_unidad_medida' => 'unidad medida',
        'fk_id_impuesto' => 'impuesto',
        'descuento_detalle' => 'descuento producto'
    ];

    public function sku()
    {
        return $this->hasOne('App\Http\Models\Inventarios\Productos','id_sku','fk_id_sku');
    }

    public function upc()
    {
        return $this->hasOne('App\Http\Models\Inventarios\Upcs','id_upc','fk_id_upc');
    }

    public function impuesto()
    {
        return $this->hasOne('App\Http\Models\Administracion\Impuestos','id_impuesto','fk_id_impuesto');
    }

    public function proyecto()
    {
        return $this->hasOne('App\Http\Models\Proyectos\Proyectos','id_proyecto','fk_id_proyecto');
    }

    public function oferta()
    {
        return $this->belongsTo('App\Http\Models\Compras\Ofertas','fk_id_oferta','id_oferta');
    }

    public function cliente()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_cliente');
    }

    public function solicitud()
    {
        return $this->hasOne('App\Http\Models\Compras\Solicitudes','id_solicitud','fk_id_solicitud');
    }

    public function unidadMedida()
    {
        return $this->hasOne('App\Http\Models\Administracion\Unidadesmedidas','id_unidad_medida','fk_id_unidad_medida');
    }
}
