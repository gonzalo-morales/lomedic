<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\ModelCompany;
use DB;

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
    protected $primaryKey = 'id_solicitud_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_solicitante','fecha_necesaria','fk_id_forma_pago','fk_id_moneda','fk_id_estatus_solicitud_pago','fk_id_sucursal','total'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_solicitud_pago' => 'Número Solicitud',
        'solicitante_formated'  => 'Solicitante',
        'sucursal.sucursal' => 'Sucursal',
        'fecha_solicitud' => 'Fecha de solicitud',
        'fecha_necesaria' => 'Fecha necesidad',
        'estatus.estatus' => 'Estatus',
    ];

    protected $dataColumns = [
        'fk_id_estatus_solicitud_pago'
    ];

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
        'fk_id_solicitante' => 'required',
        'fk_id_sucursal' => 'required',
        'fecha_necesaria' => 'required',
        'fk_id_forma_pago'=>'required',
        'fk_id_moneda'=>'required'
    ];

    public function empleado()
    {
        return $this->belongsTo('App\Http\Models\RecursosHumanos\Empleados','fk_id_solicitante','id_empleado');
    }

    public function sucursales()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_solicitud_pago');
    }

    public function detalle()
    {
        return $this->hasMany(DetalleSolicitudesPagos::class,'fk_id_solicitud_pago','id_solicitud_pago');
    }

    public function getSolicitanteFormatedAttribute() {
        return $this->empleado->nombre." ".$this->empleado->apellido_paterno." ".$this->empleado->apellido_materno;
    }

}
