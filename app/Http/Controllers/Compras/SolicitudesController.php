<?php

namespace App\Http\Controllers\Compras;

use App\Events\LogModulos;
use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Unidadesmedidas;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Solicitudes;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Ventas\FacturasClientes;
use App\Http\Models\Ventas\Pedidos;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use App\Http\Models\SociosNegocio\SociosNegocio;
use Illuminate\Support\Facades\Crypt;

class SolicitudesController extends ControllerBase
{
    public function __construct()
    {
        $this->entity = new Solicitudes;
    }
    
    public function getDataView($entity = null)
    {
        switch (\request()->tipo_documento){
            case 4:
                $detalles_documento = FacturasClientes::find(\request('id'))->detalle;
                break;
            case 8://id para pruebas: 22
                $detalles_documento = Pedidos::find(\request('id'))->detalle->where('cerrado',0);
                break;
            default:
                $detalles_documento = null;
                break;
        }
//        dd($entity->fk_id_sucursal);
//        dd(SociosNegocio::where('activo',1)->whereNotNull('fk_id_tipo_socio_compra')->whereHas('empresas',function ($q){
//            $q->where('conexion',\request()->company);
//        })->pluck('nombre_comercial','id_socio_negocio'));
        return [
            'proyectos' => Proyectos::where('fk_id_estatus',1)->orderBy('proyecto')->pluck('proyecto','id_proyecto'),
            'proveedores' => SociosNegocio::where('activo',1)->whereHas('empresas',function ($q){
                $q->where('conexion',\request()->company);
            })->whereNotNull('fk_id_tipo_socio_compra')->orderBy('nombre_comercial')->pluck('nombre_comercial','id_socio_negocio'),
            'impuestos'=> Impuestos::select('id_impuesto','impuesto')->where('activo',1)->orderBy('impuesto')->pluck('impuesto','id_impuesto'),
            'unidadesmedidas' => Unidadesmedidas::select('nombre','id_unidad_medida')->where('activo',1)->orderBy('nombre')->pluck('nombre','id_unidad_medida'),
            'skus' => Productos::where('activo',1)->orderBy('sku')->pluck('sku','id_sku'),
            'usuarios' => Usuarios::where('activo',1)->orderBy('usuario')->pluck('usuario','id_usuario'),
            'js_sucursales' => Crypt::encryptString('"conditions":[ {"where":["activo","1"]}],"whereHas": [{"usuario_sucursales":{"where":["fk_id_usuario", "$usuario"]}}]'),
            'js_usuarios' => Crypt::encryptString('"conditions":[ {"where":["activo","1"]}, {"where":["id_usuario",$usuario]}],"with": ["empleado"]'),
            'js_proveedores' => Crypt::encryptString('"select":["id_socio_negocio as id","nombre_comercial as text"],"whereHas":[{"productos":{"where":["fk_id_sku",$id_sku]}}]'),
            'detalles_documento'=>$detalles_documento
        ];
    }

    public function store(Request $request, $company, $compact = false)
    {
        # ¿Usuario tiene permiso para crear?
        $this->authorize('create', $this->entity);
        if(!isset($request->fk_id_solicitante)){
            $id_empleado = Usuarios::find(Auth::id())->fk_id_empleado;
            $request->request->set('fk_id_solicitante',$id_empleado);
        }
        $request->request->set('fecha_creacion',Carbon::now()->toDateString());
        // $fk_id_departamento = Usuarios::find()
        // $request->request->set('fk_id_departamento',Empleados::find($request->fk_id_solicitante)->fk_id_departamento);
        $request->request->set('fk_id_estatus_solicitud',1);//Al estarse creando por primer vez, tiene que estar activa
//        dd($request->request,$this->entity->rules);
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);

        $isSuccess = $this->entity->create($request->all());

        if ($isSuccess) {
            if(isset($request->_detalles)) {
                foreach ($request->_detalles as $detalle) {
                    if(empty($detalle['fk_id_upc'])){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(empty($detalle['fk_id_proyecto'])){
                        $detalle['fk_id_proyecto'] = null;
                    }
                    if(empty($detalle['fk_id_proveedor'])){
                        $detalle['fk_id_proveedor'] = null;
                    }
                    $isSuccess->detalleSolicitudes()->save(new DetalleSolicitudes($detalle));
                }
            }

            Cache::tags(getCacheTag('index'))->flush();
            event(new LogModulos($isSuccess, $company, 'crear' , 'Registro creado'));
            return $this->redirect('store');
        } else {
            event(new LogModulos($isSuccess, $company, 'crear' , 'Error al crear registro'));
            return $this->redirect('error_store');
        }
    }

    public function update(Request $request, $company, $id, $compact = false)
    {
        # ¿Usuario tiene permiso para actualizar?
        $this->authorize('update', $this->entity);
        $entity = $this->entity->findOrFail($id);

        $request->request->set('fk_id_estatus_solicitud',$entity->fk_id_estatus_solicitud);
        $request->request->set('fecha_creacion',$entity->fecha_creacion);
//        dd($request->request,$this->entity->rules);
        # Validamos request, si falla regresamos atrás
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);

        $entity->fill($request->all());
        if ($entity->save()) {
            if(isset($request->detalles)) {
                foreach ($request->detalles as $detalle) {
                    $solicitud_detalle = $entity
                        ->findOrFail($id)
                        ->detalleSolicitudes()
                        ->where('id_documento_detalle', $detalle['id_documento_detalle'])
                        ->first();
                    if(empty($detalle['fk_id_proyecto'])){
                        $detalle['fk_id_proyecto'] = null;
                    }
                    $solicitud_detalle->fill($detalle);
                    $solicitud_detalle->save();
                }
            }
            if(isset($request->_detalles)){
                foreach ($request->_detalles as $detalle){
                    if(empty($detalle['fk_id_upc'])){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(empty($detalle->fk_id_proyecto)){
                        $detalle['fk_id_proyecto'] = null;
                    }
                    if(empty($detalle->fk_id_proveedor)){
                        $detalle['fk_id_proveedor'] = null;
                    }
                    $entity->detalleSolicitudes()->save(new DetalleSolicitudes($detalle));
                }
            }

            Cache::tags(getCacheTag('index'))->flush();
            event(new LogModulos($entity, $company, 'editar', 'Registro actualizado'));
            return $this->redirect('update');
        } else {
            event(new LogModulos($entity, $company, 'editar', 'Error al editar el registro'));
            return $this->redirect('error_update');
        }
    }

    public function destroy(Request $request, $company, $idOrIds, $attributes = [])
    {
        if (!is_array($idOrIds)) {
            $isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
                ->update(['fk_id_estatus_solicitud' => 3,
                    'motivo_cancelacion'=>$request->motivo['motivo_cancelacion'],
                    'fecha_cancelacion'=>DB::raw('now()')]);
            if ($isSuccess) {

                #$this->log('destroy', $idOrIds);

                if ($request->ajax()) {
                    # Respuesta Json
                    return response()->json([
                        'success' => true,
                    ]);
                } else {
                    return $this->redirect('destroy');
                }

            } else {

                #$this->log('error_destroy', $idOrIds);

                if ($request->ajax()) {
                    # Respuesta Json
                    return response()->json([
                        'success' => false,
                    ]);
                } else {
                    return $this->redirect('error_destroy');
                }
            }

            # Multiple
        } else {

            $isSuccess = $this->entity->whereIn($this->entity->getKeyName(), $idOrIds)
                ->update(['fk_id_estatus_solicitud' => 3,
                    'motivo_cancelacion'=>$request->motivo_cancelacion,
                    'fecha_cancelacion'=>DB::raw('now()')]);
            if ($isSuccess) {

                # Shorthand
                #foreach ($idOrIds as $id) $this->log('destroy', $id);

                if ($request->ajax()) {
                    # Respuesta Json
                    return response()->json([
                        'success' => true,
                    ]);
                } else {
                    return $this->redirect('destroy');
                }

            } else {

                # Shorthand
                #foreach ($idOrIds as $id) $this->log('error_destroy', $id);

                if ($request->ajax()) {
                    # Respuesta Json
                    return response()->json([
                        'success' => false,
                    ]);
                } else {
                    return $this->redirect('error_destroy');
                }
            }
        }
    }

    public function impress($company,$id)
    {
        $solicitud = Solicitudes::where('id_documento',$id)->first();
//        $detalles = DetalleSolicitudes::where('fk_id_documento',$id)
//            ->where('cerrado','f')->get();
        $subtotal = 0;
        $iva = 0;
        $total = 0;
        foreach ($solicitud->detalleSolicitudes as $detalle)
        {
            $subtotal += $detalle->precio_unitario * $detalle->cantidad;
            $iva += (($detalle->precio_unitario*$detalle->cantidad)*$detalle->impuesto->porcentaje)/100;
            $total += $detalle->importe;
        }
        $total = number_format($total,2,'.',',');

        $barcode = DNS1D::getBarcodePNG($solicitud->id_documento,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$solicitud->id_documento])), "QRCODE");

        $empresa = Empresas::where('conexion','LIKE',$company)->first();

        $pdf = PDF::loadView(currentRouteName('compras.solicitudes.imprimir'),[
            'solicitud' => $solicitud,
//            'detalles' => $detalles,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'importe' => $total,
            'total_letra' => num2letras($total),
            'barcode' => $barcode,
            'qr' => $qr,
            'empresa' => $empresa,
            'total' => $total
        ]);

        $pdf->setPaper('letter','landscape');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(38,580,"Página {PAGE_NUM} de {PAGE_COUNT}",null,8,array(0,0,0));
        $canvas->text(665,580,'PSAI-PN06-F01 Rev. 01',null,8);
//        $canvas->image('data:image/png;charset=binary;base64,'.$barcode,355,580,100,16);

        return $pdf->stream('solicitud')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
    }
}

