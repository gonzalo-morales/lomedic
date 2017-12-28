<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 11/10/2017
 * Time: 12:00
 */

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TiposDocumentos;
use App\Http\Models\Inventarios\Entradas;
use App\Http\Models\Inventarios\EntradaDetalle;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EntradasController extends ControllerBase
{
    public function __construct(Entradas $entity)
    {
        $this->entity = $entity;
    }
    
    public function getDataView($entity = null)
    {
        
        
        
        if($entity == null)
        {
            $datos_documento = '';
        }
        else {
            switch ($entity->fk_id_tipo_documento)
            {
                    case 1:
                        $datos_documento = '';
                        break;
                    case 2:
                        $datos_documento = '';
                        break;
                    case 3:
                        $datos_documento = Ordenes::where('id_orden',$entity->numero_documento)->first();
                        break;
            }
        }

        if($datos_documento != '')
        {
            $datos_orden['sucursales'] = $datos_documento->sucursales;
            $datos_orden['proveedor'] = $datos_documento->proveedor;
            $detalle_documento = $datos_documento->detalleOrdenes;

            foreach ( $datos_documento->detalleOrdenes as $id_row => $detalle)
            {
                $detalle_productos[$id_row]['sku'] = $detalle->sku->sku;
                $detalle_productos[$id_row]['sku_descripcion'] = $detalle->sku->descripcion;
                $detalle_productos[$id_row]['upc'] = $detalle->upc['upc'];
                $detalle_productos[$id_row]['nombre_cliente'] = $detalle->cliente['nombre_comercial'];
                $detalle_productos[$id_row]['nombre_proyecto'] = $detalle->proyecto['proyecto'];
                $detalle_productos[$id_row]['cantidad'] = $detalle->cantidad;
                $detalle_productos[$id_row]['cantidad_surtida'] = $detalle->sumatoriaCantidad($entity->fk_id_tipo_documento,$entity->numero_documento,$detalle->fk_id_sku,$detalle->fk_id_upc,$detalle->id_orden_detalle);
                $detalle_productos[$id_row]['lote'] = $detalle->entradaDetalle['lote'];
                $detalle_productos[$id_row]['fecha_caducidad'] = $detalle->entradaDetalle['fecha_caducidad'];
            }

            $data = [
                'datos_documento' => $datos_orden ,
                'detalle_documento' => $detalle_documento,
                'dato_entrada'=>$datos_documento,
                'detalle_entrada'=>$detalle_productos];

        }
        else
        {
            $data = [];
        }

        return $data ;
    }

    public function create($company, $attributes =[])
    {

        $attributes = $attributes+['dataview'=>[
                'sucursales' => Sucursales::where('activo',true)->where('eliminar',false)->pluck('sucursal','id_sucursal'),
                'tipo_documento' => DB::table('gen_cat_tipo_documento')->pluck('nombre_documento','id_tipo_documento'),
        ]];
        return parent::create($company,$attributes);
    }

    public function store(Request $request, $company)
    {

        $nueva_entrada = Entradas::create(['fk_id_tipo_documento'=>$_POST['fk_id_tipo_documento'],
            'numero_documento'=> $_POST['numero_documento'],
            'referencia_documento'=> $_POST['referencia_documento'],
            'fecha_entrada'=> date("Y-m-d H:i:s")
        ]);
        parse_str($_POST['detalle_entrada'] , $datos_detalle);
        foreach ($datos_detalle["datos_entradas"] as $detalle)
        {
            if( $detalle['ingresar'] != 0 )
            {
                EntradaDetalle::create(['fk_id_entrada_almacen' => $nueva_entrada->id_entrada_almacen ,
                    'fk_id_sku' => $detalle['id_sku'],
                    'fk_id_upc' => $detalle['id_upc'],
                    'cantidad_surtida' => $detalle['ingresar'],
                    'lote' => $detalle['lote'],
                    'fecha_caducidad' => $detalle['caducidad'],
                    'fk_id_detalle_documento' => $detalle['id_detalle_documento'],
                ]);
            }
        }

        return $this->redirect('store');
    }

    public function getProveedores()
    {
        $ordenes_compra = SociosNegocio::where('fk_id_sucursal',$_POST['id_sucursal'])
            ->select('id_orden')
            ->pluck('id_orden')
            ->toJson();

        return $ordenes_compra;
    }



    public function getDetalleEntrada()
    {

        if($_POST['fk_id_tipo_documento'])
        {
            switch ($_POST['fk_id_tipo_documento'])
            {
                case 1:
                    $datos_documento = '';
                    break;
                case 2:
                    $datos_documento = '';
                    break;
                case 3:
                    $datos_documento = Ordenes::where('id_orden',$_POST['numero_documento'])
                        ->first();
                    break;
            }
        }

        if($datos_documento != '')
        {


            $datos_orden['sucursales'] = $datos_documento->sucursales;
            $datos_orden['proveedor'] = $datos_documento->proveedor;
            $detalle_documento = $datos_documento->detalleOrdenes;

            foreach ( $datos_documento->detalleOrdenes as $id_row => $detalle)
            {
                $detalle_productos[$id_row]['id_sku'] = $detalle->fk_id_sku;
                $detalle_productos[$id_row]['sku'] = $detalle->sku->sku;
                $detalle_productos[$id_row]['sku_descripcion'] = $detalle->sku->descripcion;
                $detalle_productos[$id_row]['id_upc'] = $detalle->fk_id_upc;
                $detalle_productos[$id_row]['upc'] = $detalle->upc['upc'];
                $detalle_productos[$id_row]['id_cliente'] = $detalle->fk_id_cliente;
                $detalle_productos[$id_row]['nombre_cliente'] = $detalle->cliente['nombre_comercial'];
                $detalle_productos[$id_row]['fk_id_proyecto'] = $detalle->fk_id_proyecto;
                $detalle_productos[$id_row]['nombre_proyecto'] = $detalle->proyecto['proyecto'];
                $detalle_productos[$id_row]['cantidad'] = $detalle->cantidad;
                $detalle_productos[$id_row]['id_detalle'] = $detalle->id_orden_detalle;
                $detalle_productos[$id_row]['cantidad_surtida'] = $detalle->sumatoriaCantidad($_POST['fk_id_tipo_documento'],$_POST['numero_documento'],$detalle->fk_id_sku,$detalle->fk_id_upc,$detalle->id_orden_detalle);
                $detalle_productos[$id_row]['lote'] = $detalle->entradaDetalle['lote'];
                $detalle_productos[$id_row]['fecha_caducidad'] = $detalle->entradaDetalle['fecha_caducidad'];
            }

            $data = [
                    'datos_documento' => $datos_orden ,
                    'detalle_documento' => $detalle_documento,
                    'company_route'=>companyRoute('store'),
                    'dato_entrada'=>$datos_documento,
                    'detalle_entrada'=>$detalle_productos];

        }
        else
        {
            $data = [];
        }

        return $data ;
    }


}
