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
    protected $fillable = ['fk_id_socio_negocio','fk_id_sucursal','fk_id_condicion_pago','fecha_creacion','fecha_estimada_entrega',
        'fecha_cancelacion','motivo_cancelacion','fk_id_estatus_orden','fk_id_tipo_entrega','fk_id_empresa',
        'tiempo_entrega'];

    public $niceNames =[
        'fk_id_socio_negocio'=>'proveedor'
    ];

    protected $dataColumns = [
        'fk_id_estatus_orden'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_orden' => 'Número Solicitud',
        'proveedor.nombre_corto' => 'Proveedor',
        'sucursales.sucursal' => 'Sucursal entrega',
        'fecha_creacion' => 'Fecha del pedido',
        'fecha_estimada_entrega' => 'Fecha de entrega',
        'estatus.estatus' => 'Estatus de la orden',
        'empresa.nombre_comercial' => 'Empresa'
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
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne('App\Http\Models\Compras\EstatusSolicitudes','id_estatus','fk_id_estatus_orden');
    }

    public function detalleOrdenes()
    {
        return $this->hasMany('App\Http\Models\Compras\DetalleOrdenes','fk_id_orden', 'id_orden');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Empresas','fk_id_empresa','id_empresa');
    }

    public function tipoEntrega()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\TiposEntrega','id_tipo_entrega','fk_id_tipo_entrega');
    }

    public function proveedor()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_socio_negocio');
    }
}
