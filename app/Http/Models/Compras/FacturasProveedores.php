<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\MetodosPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;

class FacturasProveedores extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fac_opr_facturas_proveedores';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_documento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_socio_negocio',
        'archivo_xml',
        'archivo_pdf',
        'fk_id_sucursal',
        'observaciones',
        'serie_factura',
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
        'motivo_cancelacion',
        'fk_id_metodo_pago',
        'version_sat',
        'folio_factura'
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
        'fk_id_estatus_factura'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_documento' => 'Número de factura',
        'serie_folio' => 'Serie y Folio',
        'proveedor.nombre_comercial' => 'Proveedor',
        'sucursal.sucursal' => 'Sucursal',
        'fecha_factura'=>'Fecha creación',
        'fecha_vencimiento' => 'Vigencia',
        //'estatus.estatus' => 'Estatus'
    ];

    public function getSerieFolioAttribute()
    {
        return $this->serie_factura.' '.$this->folio_factura;
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class,'fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_factura');
    }

    public function proveedor()
    {
        return $this->belongsTo(SociosNegocio::class,'fk_id_socio_negocio','id_socio_negocio');
    }

    public function moneda()
    {
        return $this->hasOne(Monedas::class,'id_moneda','fk_id_moneda');
    }

    public function forma_pago()
    {
        return $this->hasOne(FormasPago::class,'id_forma_pago','fk_id_forma_pago');
    }

    public function metodo_pago()
    {
        return $this->hasOne(MetodosPago::class,'id_metodo_pago','fk_id_metodo_pago');
    }

    public function detalle(){
        return $this->hasMany(DetalleFacturasProveedores::class,'fk_id_documento','id_documento');
    }

    public function detallePagos(){
        return $this->hasMany(DetallePagos::class,'fk_id_documento','id_documento');
    }

    public function notas()
    {//Tiene muchas notas por medio de cfdi
        return $this->hasManyThrough(NotasCreditoProveedor::class,CfdiRelacionesProveedores::class,'fk_id_documento_relacionado','id_documento','id_documento','fk_id_documento')->where('fac_det_cfdi_relaciones_proveedores.fk_id_tipo_documento',11)->where('fac_det_cfdi_relaciones_proveedores.fk_id_tipo_documento_relacionado',7);
        //Tiene notas de crédito por medio de relaciones de proveedores. ID del documento relacionado. ID de la nota en la tabla de notas. ID de la factura en la tabla de facturas. ID de la factura en la tabla de relaciones
    }
}
