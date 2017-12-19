<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Unidadesmedidas;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Solicitudes;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\RecursosHumanos\Empleados;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use App\Http\Models\SociosNegocio\SociosNegocio;

class SolicitudesController extends ControllerBase
{
    public function __construct(Solicitudes $entity)
    {
        $this->entity = $entity;
    }

    public function index($company, $attributes = [])
    {
        $attributes = ['dataview'=>[
            'company'=>$company
        ]];
        return parent::index($company, $attributes);
    }

    public function create($company,$attributes =[])
    {
        $proveedores = SociosNegocio::where('activo', 1)->whereNotNull('fk_id_tipo_socio_compra')->pluck('nombre_comercial','id_socio_negocio');
        $attributes = $attributes+['dataview'=>[
                'sucursalesempleado' => $this->entity->first()
                    ->empleado()->first()
                    ->sucursales()->get()
                    ->pluck('sucursal','id_sucursal'),
                'detalles' => $this->entity
                    ->first()
                    ->detalleSolicitudes()->where('cerrado','0')->get(),
                'impuestos'=> Impuestos::select('id_impuesto','impuesto')
                    ->where('activo',1)
                    ->get()
                    ->pluck('impuesto','id_impuesto'),
                'unidadesmedidas' => Unidadesmedidas::select('nombre','id_unidad_medida')
                    ->where('activo',1)
                    ->get()
                    ->pluck('nombre','id_unidad_medida'),
                'empleados' => Empleados::select(DB::raw("CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) as nombre"),'id_empleado')
                    ->where('activo',1)
                    ->get()
                    ->pluck('nombre','id_empleado'),
                'skus' => Productos::where('activo','1')
                    ->get()
                    ->pluck('sku','id_sku'),
                'proyectos' => Proyectos::where('fk_id_estatus',1)
                    ->get()
                    ->pluck('proyecto','id_proyecto'),
                'proveedores' => $proveedores,
            ]];
        return parent::create($company,$attributes);
    }

    public function store(Request $request, $company)
    {
        $id_empleado = Usuarios::where('id_usuario', Auth::id())->first()->fk_id_empleado;
        # ¿Usuario tiene permiso para crear?
        $this->authorize('create', $this->entity);
        if(!isset($request->fk_id_solicitante)){
            $request->request->set('fk_id_solicitante',$id_empleado);
        }

        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

        $request->request->set('fecha_creacion',DB::raw('now()'));

        $request->request
            ->set('fk_id_departamento',Empleados::where('id_empleado',$request->fk_id_solicitante)
                ->first()
                ->fk_id_departamento);
        $request->request->set('fk_id_estatus_solicitud',1);//Al estarse creando por primer vez, tiene que estar activa

        $isSuccess = $this->entity->create($request->all());

        if ($isSuccess) {
            if(isset($request->_detalles)) {
                foreach ($request->_detalles as $detalle) {
                    if(empty($detalle->fk_id_upc)){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(empty($detalle->fk_id_proyecto)){
                        $detalle['fk_id_proyecto'] = null;
                    }
                    if(empty($detalle->fk_id_proveedor)){
                        $detalle['fk_id_proveedor'] = null;
                    }
                    $isSuccess->detalleSolicitudes()->save(new DetalleSolicitudes($detalle));
                }
                $this->log('store', $isSuccess->id_solicitud);
            }
            return $this->redirect('store');
        } else {
            $this->log('error_store');
            return $this->redirect('error_store');
        }
    }

    public function show($company,$id,$attributes = [])
    {
        $proveedores = SociosNegocio::where('activo', 1)->whereNotNull('fk_id_tipo_socio_compra')->pluck('nombre_comercial','id_socio_negocio');
        $attributes = $attributes+['dataview'=>[
                'sucursalesempleado' => $this->entity
                    ->where('id_solicitud',$id)->first()
                    ->empleado()->first()
                    ->sucursales()->get()
                    ->pluck('nombre_sucursal','id_sucursal'),
                'detalles' => $this->entity
                    ->where('id_solicitud',$id)
                    ->first()
                    ->detalleSolicitudes()->where('cerrado','0')->get(),
                'impuestos'=> Impuestos::select('id_impuesto','impuesto')
                    ->where('activo',1)
                    ->get()
                    ->pluck('impuesto','id_impuesto'),
                'empleados' => Empleados::select(DB::raw("CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) as nombre"),'id_empleado')
                    ->where('activo',1)
                    ->get()
                    ->pluck('nombre','id_empleado'),
                'proveedores' => $proveedores,
                'company' => $company
            ]];
        return parent::show($company,$id,$attributes);
    }

    public function edit($company,$id,$attributes = [])
    {
        $proveedores = SociosNegocio::where('activo', 1)->whereNotNull('fk_id_tipo_socio_compra')->pluck('nombre_comercial','id_socio_negocio');

        $attributes = $attributes+['dataview'=>[
                'sucursalesempleado' => $this->entity
                    ->where('id_solicitud',$id)->first()
                    ->empleado()->first()
                    ->sucursales()->get()
                    ->pluck('nombre_sucursal','id_sucursal'),
                'detalles' => $this->entity
                    ->where('id_solicitud',$id)
                    ->first()
                    ->detalleSolicitudes()->where('cerrado','f')->get(),
                'impuestos'=> Impuestos::select('id_impuesto','impuesto')
                    ->where('activo',1)
                    ->get()
                    ->pluck('impuesto','id_impuesto'),
                'proyectos'=> Proyectos::select('proyecto', 'id_proyecto')
                    ->where('fk_id_estatus',1)
                    ->get()
                    ->pluck('proyecto','id_proyecto'),
                'unidadesmedidas' => Unidadesmedidas::select('nombre','id_unidad_medida')
                    ->where('activo',1)
                    ->get()
                    ->pluck('nombre','id_unidad_medida'),
                'skus' => Productos::where('activo','1')
                    ->get()
                    ->pluck('sku','id_sku'),
                'empleados' => Empleados::select(DB::raw("CONCAT(nombre,' ',apellido_paterno,' ',apellido_materno) as nombre"),'id_empleado')
                    ->where('activo',1)
                    ->get()
                    ->pluck('nombre','id_empleado'),
                'proveedores' => $proveedores
            ]];
        return parent::edit($company, $id, $attributes);
    }

    public function update(Request $request, $company, $id)
    {
        # ¿Usuario tiene permiso para actualizar?
        $this->authorize('update', $this->entity);

        # Validamos request, si falla regresamos atras
        $this->validate($request, $this->entity->rules);

        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        if ($entity->save()) {
            if(isset($request->detalles)) {
                foreach ($request->detalles as $detalle) {
                    $solicitud_detalle = $entity
                        ->findOrFail($id)
                        ->detalleSolicitudes()
                        ->where('id_solicitud_detalle', $detalle['id_solicitud_detalle'])
                        ->first();
                    if(empty($detalle->fk_id_proyecto)){
                        $detalle['fk_id_proyecto'] = null;
                    }
                    $solicitud_detalle->fill($detalle);
                    $solicitud_detalle->save();
                }
            }
            if(isset($request->_detalles)){
                foreach ($request->_detalles as $detalle){
                    if(empty($detalle->fk_id_upc)){
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

            $this->log('update', $id);
            return $this->redirect('update');
        } else {
            $this->log('error_update', $id);
            return $this->redirect('error_update');
        }
    }

    public function destroy(Request $request, $company, $idOrIds)
    {
        if (!is_array($idOrIds)) {
            $isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
                ->update(['fk_id_estatus_solicitud' => 3,
                    'motivo_cancelacion'=>$request->motivo['motivo_cancelacion'],
                    'fecha_cancelacion'=>DB::raw('now()')]);
            if ($isSuccess) {

                $this->log('destroy', $idOrIds);

                if ($request->ajax()) {
                    # Respuesta Json
                    return response()->json([
                        'success' => true,
                    ]);
                } else {
                    return $this->redirect('destroy');
                }

            } else {

                $this->log('error_destroy', $idOrIds);

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
                foreach ($idOrIds as $id) $this->log('destroy', $id);

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
                foreach ($idOrIds as $id) $this->log('error_destroy', $id);

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
        $solicitud = Solicitudes::where('id_solicitud',$id)->first();
        $detalles = DetalleSolicitudes::where('fk_id_solicitud',$id)
            ->where('cerrado','f')->get();
        $subtotal = 0;
        $iva = 0;
        $total = 0;
        foreach ($detalles as $detalle)
        {
            $subtotal += $detalle->precio_unitario * $detalle->cantidad;
            $iva += (($detalle->precio_unitario*$detalle->cantidad)*$detalle->impuesto->porcentaje)/100;
            $total += $detalle->total;
        }
        $total = number_format($total,2,'.',',');

        $barcode = DNS1D::getBarcodePNG($solicitud->id_solicitud,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$solicitud->id_solicitud])), "QRCODE");

        $empresa = Empresas::where('conexion','LIKE',$company)->first();

        $pdf = PDF::loadView(currentRouteName('compras.solicitudes.imprimir'),[
            'solicitud' => $solicitud,
            'detalles' => $detalles,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'total_letra' => num2letras($total),
            'barcode' => $barcode,
            'qr' => $qr,
            'empresa' => $empresa
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

