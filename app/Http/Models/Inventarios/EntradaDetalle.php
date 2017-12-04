<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 13/11/2017
 * Time: 11:37
 */


namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;
use DB;

class EntradaDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_det_entrada_almacen';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_entrada';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_entrada_almacen','fk_id_lote','fk_id_sku','fk_id_upc','cantidad_surtida','fecha_caducidad','lote','fk_id_detalle_documento'];

//    public $niceNames =[
//        'fk_id_socio_negocio'=>'proveedor'
//    ];
//
//    protected $dataColumns = [
//        'fk_id_estatus_orden'
//    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_detalle_entrada' => '#',
        'fk_id_entrada_almacen' => 'Numero entrada',
        'lote' => 'Numero de lote',
        'fk_id_sku' => 'Sku',
        'fk_id_upc' => 'Upc',
        'cantidad_surtida' => 'Cantidad surtida'
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
//    public $rules = [
//        'fk_id_socio_negocio' => 'required',
//        'fk_id_sucursal' => 'required',
//        'fk_id_condicion_pago' => 'required',
//        'fk_id_tipo_entrega' => 'required'
//    ];

    public function sucursales()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucurñsal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne('App\Http\Models\Compras\EstatusSolicitudes','id_estatus','fk_id_estatus_orden');
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

    public function detalleOrdenes()
    {
        return $this->hasMany('App\Http\Models\Compras\DetalleOrdenes','fk_id_orden', 'id_orden');
    }


}
