<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\EstatusDocumentos;
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
        'tiempo_entrega','importacion','impuesto','subtotal','total_orden','descuento_general','descuento_total'];

    public $niceNames =[
        'fk_id_socio_negocio'=>'proveedor',
        'fk_id_sucursal'=>'sucursal',
        'fk_id_condicion_pago'=>'condición pago',
        'fk_id_tipo_entrega'=>'tipo entrega'
    ];

    protected $dataColumns = [
        'fk_id_estatus_orden',
        'fk_id_estatus_autorizacion'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_orden' => 'Número Solicitud',
        'proveedor.nombre_comercial' => 'Proveedor',
        'sucursales.sucursal' => 'Sucursal entrega',
        'fecha_creacion' => 'Fecha del pedido',
        'fecha_estimada_entrega' => 'Fecha de entrega',
        'estatus.estatus' => 'Estatus de la orden',
        'estatusautorizacion.estatus' => 'Estatus autorización',
        'empresa.nombre_comercial' => 'Empresa'
    ];

    protected $eagerLoaders = ['proveedor','sucursales','estatus','empresa'];

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
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_orden');
    }

    public function detalleOrdenes()
    {
        return $this->hasMany('App\Http\Models\Compras\DetalleOrdenes','fk_id_documento', 'id_orden');
        // ->whereNotNull('fk_id_documento');
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

    public function tipoDocumento()
    {
        return $this->hasOne('App\Http\Models\Administracion\TiposDocumentos','id_tipo_documento','fk_id_tipo_documento');
    }
    public function detalleSku()
    {
        return $this->hasOne('App\Http\Models\Inventarios\Productos','id_sku','fk_id_sku');
    }

    public function detalleUpc()
    {
        return $this->hasOne('App\Http\Models\Inventarios\Upcs','id_upc','fk_id_upc');
    }

    public function usuarios()
    {
        return $this->hasOne(Usuarios::class,'fk_id_usuario','id_usuario');
    }

    public function autorizaciones()
    {
        return $this->hasMany(Autorizaciones::class,'fk_id_documento','id_orden');
    }

    public function estatusautorizacion()
    {
        return $this->hasOne(EstatusAutorizaciones::class,'id_estatus','fk_id_estatus_autorizacion');
    }
}
