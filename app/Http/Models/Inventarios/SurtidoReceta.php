<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 28/12/2017
 * Time: 01:46
 */

namespace App\Http\Models\Inventarios;

use App\Http\Models\Inventarios\SurtidoRequisicionHospitalariaDetalle;
use App\Http\Models\ModelCompany;
use Illuminate\Support\Facades\Auth;
use DB;

class SurtidoReceta extends ModelCompany
{

    protected $table = 'rec_opr_surtido_receta';


    protected $primaryKey = 'id_surtido_receta';


    protected $fillable = [
        'observaciones',
        'fk_id_usuario_captura',
        'fk_id_usuario_modificacion',
        'fecha_modificacion',
        'fk_id_surtido_receta',
        'fk_id_requisicion_hospitalaria',
        'fecha_surtido',
        'cancelado',
        'fecha_cancelado',
        'motivo_cancelado',
        'fk_id_usuario_cancelado',
        'eliminado',
        'fk_id_estatus_surtido',
    ];

//    public $niceNames =[
//        'fk_id_socio_negocio'=>'proveedor'
//    ];
//
//    protected $dataColumns = [
//        'fk_id_estatus_orden'
//    ];

    protected $fields = [
        'id_surtido_receta' => '#',
        'observaciones' => 'observaciones',
        'fecha_surtido' => 'Fecha surtido',
        'estatus.estatus_surtido' => 'Estatus'
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


    public $rules = [
//        'fk_id_socio_negocio' => 'required',
//        'fk_id_sucursal' => 'required',
//        'fk_id_condicion_pago' => 'required',
//        'fk_id_tipo_entrega' => 'required'
    ];

//    public function sucursales()
//    {
//        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucurñsal','id_sucursal');
//    }
//
    public function estatus()
    {
        return $this->hasOne('App\Http\Models\Inventarios\EstatusSurtido','id_estatus_surtido','fk_id_estatus_surtido');
    }

//
//
//
//    public function empresa()
//    {
//        return $this->belongsTo('App\Http\Models\Administracion\Empresas','fk_id_empresa','id_empresa');
//    }
//
//    public function tipoEntrega()
//    {
//        return $this->hasOne('App\Http\Models\SociosNegocio\TiposEntrega','id_tipo_entrega','fk_id_tipo_entrega');
//    }
//
//    public function proveedor()
//    {
//        return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_socio_negocio');
//    }
//
//    public function detalleOrdenes()
//    {
//        return $this->hasMany('App\Http\Models\Compras\DetalleOrdenes','fk_id_orden', 'id_orden');
//    }

    public function detalles()
    {
        return $this->hasMany(SurtidoRecetaDetalle::class,'fk_id_surtido_receta','id_surtido_receta');
    }

}
