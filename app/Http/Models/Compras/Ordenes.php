<?php

namespace App\Http\Models\Compras;

use App\Http\Models\ModelCompany;
use DB;

class Ordenes extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_opr_ordenes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_orden';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_solicitante','fk_id_sucursal','fk_id_departamento','fecha_creacion','fecha_necesidad',
        'fecha_cancelacion','motivo_cancelacion','fk_id_estatus_solicitud','fk_id_socio_negocio','fk_id_condicion_pago',
        'importe','fk_id_tipo_entrega'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_solicitud' => 'Número Solicitud',
        'nombre_completo'  => 'Solicitante',
        'nombre_sucursal' => 'Sucursal',
        'fecha_creacion' => 'Fecha de solicitud',
        'fecha_necesidad' => 'Fecha necesidad',
        'estatus_solicitud' => 'Estatus'
    ];

    function getNombreCompletoAttribute() {
        return $this->empleado->nombre.' '.$this->empleado->apellido_paterno.' '.$this->empleado->apellido_materno;
    }

    function getNombreSucursalAttribute(){
        return $this->sucursales->nombre_sucursal;
    }

    function getEstatusSolicitudAttribute(){
        return $this->estatus->estatus;
    }

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_socio_negocio' => 'required',
        'fk_id_sucursal' => 'required',
        'fk_id_condicion_pago' => 'required',
        'fk_id_tipo_entrega' => 'required'
    ];

    public function sucursales()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','id_sucursal','fk_id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne('App\Http\Models\Compras\EstatusSolicitudes','id_estatus','fk_id_estatus_solicitud');
    }

    public function detalleSolicitudes()
    {
        return $this->hasMany('App\Http\Models\Compras\DetalleSolicitudes','fk_id_solicitud', 'id_solicitud');
    }

    public function getSolicitanteFormatedAttribute() {
        return $this->empleado->nombre." ".$this->empleado->apellido_paterno." ".$this->empleado->apellido_materno;
    }

    public function empresa()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Empresas','id_empresa','fk_id_empresa');
    }

    public function tipoEntrega()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\TiposEntrega','id_tipo_entrega','fk_id_tipo_entrega');
    }

    public function proveedor()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\TiposEntrega','id_socio_negocio','fk_id_proveedor');
    }

}
