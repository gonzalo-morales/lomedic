<?php

namespace App\Http\Models\Compras;

use App\Http\Models\ModelCompany;
use DB;

class DetalleSolicitudesPagos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_det_solicitudes_pagos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_solicitud_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cantidad','descripcion','impuesto','precio_unitario','importe','fk_id_orden_compra','fk_id_solicitud_pago'];

}
