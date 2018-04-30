<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 28/12/2017
 * Time: 01:46
 */

namespace App\Http\Models\Inventarios;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Administracion\Sucursales;
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
        'fk_id_receta',
        'fecha_surtido',
        'cancelado',
        'fecha_cancelado',
        'motivo_cancelado',
        'fk_id_usuario_cancelado',
        'eliminado',
        'fk_id_estatus_surtido',
        'fk_id_sucursal'
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
        'sucursal.sucursal' => 'Sucursal',
        'fk_id_receta' => 'Receta',
        'fecha_surtido' => 'Fecha surtido',
        'estatus_documento_span' => 'Estatus',
        'observaciones' => 'observaciones',
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

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class,'fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_surtido');
    }

    public function detalles()
    {
        return $this->hasMany(SurtidoRecetaDetalle::class,'fk_id_surtido_receta','id_surtido_receta');
    }

}
