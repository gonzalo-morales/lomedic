<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\RecursosHumanos\Empleados;

class SolicitudesPagos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_opr_solicitudes_pagos';

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
    protected $fillable = ['fk_id_solicitante','fecha_necesaria','fk_id_forma_pago','fk_id_moneda',
        'fk_id_estatus_solicitud_pago','fk_id_sucursal','total'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_documento' => 'Número Solicitud',
        'solicitante_formated'  => 'Solicitante',
        'sucursales.sucursal' => 'Sucursal',
        'fecha_solicitud_formated' => 'Fecha de solicitud',
        'fecha_necesaria' => 'Fecha necesidad',
        'estatus_documento_span' => 'Estatus',
        'estatus_autorizacion_span' => 'Estatus Autorización'
    ];

    protected $dataColumns = [
        'fk_id_estatus_solicitud_pago'
    ];

    public function empleado()
    {
        return $this->belongsTo(Empleados::class,'fk_id_solicitante','id_empleado');
    }

    public function sucursales()
    {
        return $this->belongsTo(Sucursales::class,'fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_solicitud_pago');
    }

    public function detalle()
    {
        return $this->hasMany(DetalleSolicitudesPagos::class,'fk_id_documento','id_documento');
    }

    public function getSolicitanteFormatedAttribute() {
        return $this->empleado->nombre." ".$this->empleado->apellido_paterno." ".$this->empleado->apellido_materno;
    }

    public function autorizaciones()
    {
        return $this->hasMany(Autorizaciones::class,'fk_id_documento','id_documento');
    }

    public function estatusautorizacion()
    {
        return $this->hasOne(EstatusAutorizaciones::class,'id_estatus','fk_id_estatus_autorizacion');
    }

    public function getFechaSolicitudFormatedAttribute()
    {
        return date_format(date_create($this->fecha_solicitud),"Y-m-d H:i:s");
    }
}
