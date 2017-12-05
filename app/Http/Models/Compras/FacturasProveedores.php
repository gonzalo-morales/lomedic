<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Compras\DetalleFacturasProveedores;

class FacturasProveedores extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fact_opr_facturas_proveedores';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_factura_proveedor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_socio_negocio',
        'archivo_xml',
        'archivo_pdf',
        'fk_id_sucursal',
        'observaciones',
        'serie_folio_factura',
        'uuid',
        'fecha_factura',
        'fecha_vencimiento',
        'fk_id_forma_pago',
        'total',
        'fk_id_estatus_factura',
        'total_pagado',
        'iva',
        'subtotal',
        'fk_id_moneda',
        'motivo_cancelacion'
    ];

    public $niceNames =[
        'fk_id_socio_negocio'=>'proveedor',
        'archivo_xml'=>'xml',
        'archivo_pdf'=>'pdf',
        'fk_id_sucursal'=>'sucursal',
        'serie_folio_factura'=>'serie y folio',
        'fecha_factura'=>'fecha factura',
        'fecha_vencimiento'=>'fecha vencimiento',
        'fk_id_forma_pago'=>'forma pago',
        'fk_id_estatus_factura'=>'estatus factura',
        'total_pagado'=>'total pagado',
        'fk_id_moneda'=>'moneda',
    ];

    protected $dataColumns = [
//        'fk_id_estatus_oferta'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_factura_proveedor' => 'Número de factura',
        'serie_folio_factura' => 'Serie y Folio',
        'proveedor.nombre_comercial' => 'Proveedor',
        'sucursal.sucursal' => 'Sucursal',
        'fecha_factura'=>'Fecha creación',
        'vigencia' => 'Vigencia',
        'estatus.estatus' => 'Estatus'
    ];

//    protected $eagerLoaders = ['proveedor','sucursal'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
//        'fk_id_sucursal'=>'required',
//        'fk_id_proveedor'=>'required',
//        'fk_id_moneda'=>'required',
//        'descuento_oferta'=>'nullable||regex:/^(\d{0,2}(\.\d{0,4})?\)$/'
    ];

    public function sucursal()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne('App\Http\Models\Compras\EstatusSolicitudes','id_estatus','fk_id_estatus_factura');
    }

    public function proveedor()
    {
        return $this->belongsTo(SociosNegocio::class,'fk_id_proveedor','id_socio_negocio');
    }

    public function moneda()
    {
        return $this->hasOne('App\Http\Models\Administracion\Monedas','id_moneda','fk_id_moneda');
    }

    public function forma_pago()
    {
        return $this->hasOne(FormasPago::class,'id_forma_pago','fk_id_forma_pago');
    }

    public function detalle_facturas_proveedores(){
        return $this->hasMany(DetalleFacturasProveedores::class,'fk_id_factura_proveedor','id_factura_proveedor');
    }
}
