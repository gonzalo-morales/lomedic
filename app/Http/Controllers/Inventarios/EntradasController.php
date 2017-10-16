<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 11/10/2017
 * Time: 12:00
 */

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Localidades;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Proveedores;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\SociosNegocio\SociosNegocio;
//use App\Http\Models\Compras\Solicitudes;
//use Milon\Barcode\DNS2D;
//use Milon\Barcode\DNS1D;
//use App\Http\Models\Finanzas\CondicionesPago;
//use App\Http\Models\Proyectos\Proyectos;
//use App\Http\Models\SociosNegocio\SociosNegocio;
//use App\Http\Models\SociosNegocio\TiposEntrega;
//use Barryvdh\DomPDF\Facade as PDF;
//use Illuminate\Http\Request;
//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Response;


class EntradasController extends ControllerBase
{
    public function __construct(Ordenes $entity)
    {
        $this->entity = $entity;
    }

    public function index($company, $attributes = [])
    {
        $attributes = ['where'=>[]];
        return parent::index($company, $attributes);
    }


    public function create($company, $attributes =[])
    {


//        $clientes = SociosNegocio::where('activo', 1)->whereHas('tipoSocio', function($q) {
//            $q->where('fk_id_tipo_socio', 1);
//        })->pluck('nombre_corto','id_socio_negocio');

        $attributes = $attributes+['dataview'=>[
                'sucursales' => Sucursales::where('activo',true)->where('eliminar',false)->pluck('sucursal','id_sucursal'),
//                'ordenes_compra' => Ordenes::all()->pluck('id_orden'),
//                'proveedor' => Proveedores::all(),
//                'cliente' => Usuarios::all(),
//                'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
//                'clientes' => $clientes,
//                'proyectos' => Proyectos::where('activo',1)->pluck('proyecto','id_proyecto'),
//                'tiposEntrega' => TiposEntrega::where('activo',1)->pluck('tipo_entrega','id_tipo_entrega'),
//                'condicionesPago' => CondicionesPago::where('activo',1)->pluck('condicion_pago','id_condicion_pago'),
            ]];
        return parent::create($company,$attributes);
    }

    public function getOrdenes()
    {
        $ordenes_compra = Ordenes::where('fk_id_sucursal',$_POST['id_sucursal'])
            ->select('id_orden')
            ->pluck('id_orden')
            ->toJson();

        return $ordenes_compra;
    }

    public function getDetalleOrden()
    {
//        $detalle_orden = SociosNegocio::all()
//            ->toJson();

        $detalle_orden2 = Ordenes::where('fk_id_sucursal',$_POST['fk_id_sucursal'])->first()->toJson();
//        dd($detalle_orden2->proveedor());
//        $socios =
//        $detalle_orden = Ordenes::join(SociosNegocio::class,'com_opr_ordenes.fk_id_socio_negocio','gen_cat_socios_negocio.id_socio_negocio')
//            ->where('com_opr_ordenes.fk_id_sucursal',$_POST['fk_id_sucursal'])
//            ->where('com_opr_ordenes.id_orden',$_POST['id_orden'])
        return $detalle_orden2->proveedor();

    }


}
