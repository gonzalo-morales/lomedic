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
    protected $primaryKey = 'id_documento_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_sku','fk_id_upc','fk_id_cliente','cantidad','fk_id_unidad_medida',
        'fk_id_impuesto','precio_unitario','total_producto','fk_id_proyecto','descuento_detalle','fk_id_documento',
        'fk_id_tipo_documento_base','fk_id_documento_base','fk_id_linea'];

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
        return $this->belongsTo('App\Http\Models\Compras\Ofertas','fk_id_documento','id_documento');
    }

    public function cliente()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_cliente');
    }

    public function solicitud()
    {
        return $this->hasOne('App\Http\Models\Compras\Solicitudes','id_solicitud','fk_id_documento_base');
    }

    public function unidadMedida()
    {
        return $this->hasOne('App\Http\Models\Administracion\Unidadesmedidas','id_unidad_medida','fk_id_unidad_medida');
    }
}
