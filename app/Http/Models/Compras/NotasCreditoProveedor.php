<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\ModelCompany;
use App\Http\Models\Ventas\CfdiRelacionesClientes;
use App\Http\Models\Ventas\FacturasClientes;
use App\Http\Models\Ventas\NotasCreditoClientes;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Compras\CfdiRelacionesProveedores;

class NotasCreditoProveedor extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fac_opr_notas_credito_proveedor';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_nota_credito_proveedor';

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
        'fk_id_forma_pago',
        'total',
        'fk_id_estatus_nota',
        'iva',
        'subtotal',
        'fk_id_moneda',
        'motivo_cancelacion',
        'fk_id_metodo_pago',
        'version_sat',
        'folio_factura',
    ];

    public $niceNames =[
        'fk_id_socio_negocio'=>'proveedor',
        'archivo_xml'=>'xml',
        'archivo_pdf'=>'pdf',
        'fk_id_sucursal'=>'sucursal',
        'serie_folio_factura'=>'serie y folio',
        'fecha_factura'=>'fecha factura',
        'fk_id_forma_pago'=>'forma pago',
        'fk_id_estatus_factura'=>'estatus factura',
        'total_pagado'=>'total pagado',
        'fk_id_moneda'=>'moneda',
    ];

    protected $dataColumns = [
        'fk_id_estatus_nota'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_nota_credito_proveedor' => 'Número de Nota',
        'serie_folio' => 'Serie y Folio',
        'proveedor.nombre_comercial' => 'Proveedor',
        'sucursal.sucursal' => 'Sucursal',
        'fecha_factura'=>'Fecha creación',
        'estatus.estatus' => 'Estatus'
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_sucursal'=>'required',
        'fk_id_proveedor'=>'required',
//        'fk_id_moneda'=>'required',
//        'descuento_oferta'=>'nullable||regex:/^(\d{0,2}(\.\d{0,4})?\)$/'
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
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_nota');
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

    public function detalle(){
        return $this->hasMany(DetalleNotasCreditoProveedor::class,'fk_id_documento','id_nota_credito_proveedor');
    }
    public function facturas()
    {
        return $this->hasManyThrough(FacturasClientes::class,CfdiRelacionesClientes::class,'fk_id_documento_relacionado','id_nota_credito','id_factura','fk_id_documento');
    }

    public function notascredito()
    {
        return $this->hasManyThrough(NotasCreditoClientes::class,CfdiRelacionesClientes::class,'fk_id_documento_relacionado','id_nota_credito','id_nota_credito','fk_id_documento');
    }

    public function relaciones()
    {
        return $this->hasMany(CfdiRelacionesClientes::class,'fk_id_documento','id_nota_credito');
    }
}
