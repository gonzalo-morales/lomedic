<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\TiposDocumentos;
use App\Http\Models\ModelCompany;
use DB;

class DetallePagos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fac_det_pagos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_pago',
        'fk_id_documento',
        'monto',
        'fk_id_tipo_documento',
    ];

    public function tipodocumento()
    {
        return $this->hasOne(TiposDocumentos::class,'id_tipo_documento','fk_id_tipo_documento');
    }

    public function facturaproveedor()
    {
        return $this->hasOne(FacturasProveedores::class,'id_factura_proveedor','fk_id_documento');
    }

    public function solicitudpago()
    {
        return $this->hasOne(SolicitudesPagos::class,'id_solicitud_pago','fk_id_documento');
    }

    public function pago()
    {
        return $this->belongsTo(Pagos::class,'fk_id_pago','id_pago');
    }
}
