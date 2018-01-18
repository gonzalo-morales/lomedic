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

class SurtidoRequisicionHospitalaria extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'req_opr_surtido_requisicion_hospitalaria';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_surtido_requisicion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
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
        'cantidad_surtida'

    ];

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
    protected $eagerLoaders = [
        'estatus'
    ];

    protected $fields = [
        'id_surtido_requisicion' => '#',
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

    /**
     * The validation rules
     * @var array
     */
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
        return $this->hasMany(SurtidoRequisicionHospitalariaDetalle::class,'fk_id_surtido_requisicion','id_surtido_requisicion');
    }

}
