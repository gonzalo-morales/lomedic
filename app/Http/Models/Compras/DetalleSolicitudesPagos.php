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
    protected $primaryKey = 'id_documento_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cantidad','descripcion','impuesto','precio_unitario','importe','fk_id_linea','fk_id_tipo_documento_base','fk_id_documento'];

}
