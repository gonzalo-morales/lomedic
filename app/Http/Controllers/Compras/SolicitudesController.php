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
            case 8:
                $detalles_documento = Pedidos::find(\request('id'))->detalle->where('cerrado',0);
                break;
            default:
                $detalles_documento = null;
                break;
        }
        if (Auth::check())
        {
            // The user is logged in...
            $user = Auth::id();
            $userName = Auth::user()->usuario;
        }

        $sucursales = [];
        $proveedores = [];
        if($entity != null)
        {
            $proveedores = SociosNegocio::where('activo',1)->whereHas('empresas',function ($q) use ($entity){
                $q->where('fk_id_socio_negocio',$entity->fk_id_socio_negocio);
            })->whereNotNull('fk_id_tipo_socio_compra')->pluck('nombre_comercial','id_socio_negocio')->prepend('Seleccione el proveedor','');
            $sucursales = Sucursales::hasSucursal()->where('id_sucursal',$entity->fk_id_sucursal)->pluck('sucursal','id_sucursal');
        }
        return [
            'proyectos'         => Proyectos::where('fk_id_estatus',1)->orderBy('proyecto')->pluck('proyecto','id_proyecto')->prepend('Seleccione el proyecto',''),
            'proveedores'       => $proveedores ?? '',
            'sucursales'        => $sucursales ?? '', 
            'impuestos'         => Impuestos::select('id_impuesto','impuesto')->where('activo',1)->orderBy('impuesto')->with('porcentaje')->pluck('impuesto','id_impuesto')->prepend('Seleccione...',''),
            'unidadesmedidas'   => Unidadesmedidas::select('nombre','id_unidad_medida')->where('activo',1)->orderBy('nombre')->pluck('nombre','id_unidad_medida')->prepend('Seleccione...',''),
            'skus'              => Productos::where('activo',1)->where('articulo_compra',1)->orderBy('sku')->pluck('sku','id_sku'),
            'usuarios'          => Usuarios::where('activo',1)->orderBy('usuario')->pluck('usuario','id_usuario')->prepend('Seleccione un usuario','')->put($user,'Yo'.' ('.$userName.')'),
            // 'usuarios'       => Usuarios::where('activo',1)->orderBy('usuario')->get()->put($user,'Yo'.' ('.$userName.')'),
//            'js_sucursales'     => Crypt::encryptString('"select":["id_sucursal as id","sucursal as text"],"hasEmpresa":[],"isActivo":[]'),
             'js_sucursales'     => Crypt::encryptString('
                "select": ["id_sucursal as id","sucursal as text"],
                "isActivo": [],
                "hasEmpresa": [],
                "whereHas": [{
                    "usuario": {
                        "where": ["fk_id_usuario", "$usuario"]
                    }
                }]
                '),
            'js_usuarios'       => Crypt::encryptString('"conditions":[ {"where":["activo","1"]}, {"where":["id_usuario",$usuario]}],"with": ["empleado"]'),
            'js_proveedores'    => Crypt::encryptString('
                "select":["id_socio_negocio as id","nombre_comercial as text"],
                "isActivo": [],
                "hasEmpresa": [],
                "conditions":[{
                    "whereRaw":["(fk_id_tipo_socio_compra IS NOT NULL)"]
                }],
                "whereHas":[{
                    "productos":{
                        "where":["fk_id_sku",$id_sku]
                    }
                }]
            '),
            'js_porcentaje'     => Crypt::encryptString('"select": ["tasa_o_cuota"], "conditions": [{"where":["id_impuesto", "$id_impuesto"]}], "limit": "1"'),
            'detalles_documento'=> $detalles_documento,
        ];
    }

    public function store(Request $request, $company, $compact = false)
    {
        if(!isset($request->fk_id_solicitante)){
            $id_empleado = Usuarios::find(Auth::id())->fk_id_empleado;
            $request->request->set('fk_id_solicitante',$id_empleado);
        }
        $request->request->set('fecha_creacion',Carbon::now()->toDateString());
        $request->request->set('fk_id_estatus_solicitud',1);//Al estarse creando por primer vez, tiene que estar activa
        return parent::store($request,$company,$compact);
    }

    public function update(Request $request, $company, $id, $compact = false)
    {
        $entity = $this->entity->findOrFail($id);

        $request->request->set('fk_id_estatus_solicitud',$entity->fk_id_estatus_solicitud);
        $request->request->set('fecha_creacion',$entity->fecha_creacion);
        return parent::update($request,$company,$id,$compact);
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

        $barcode = DNS1D::getBarcodePNG($solicitud->id_documento,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$solicitud->id_documento])), "QRCODE");

        $empresa = Empresas::where('conexion','LIKE',$company)->first();
        $pdf = PDF::loadView(currentRouteName('compras.solicitudes.imprimir'),[
            'solicitud' => $solicitud,
            'empresa' => $empresa,
            'barcode' => $barcode,
            'qr' => $qr,
            ]);
            $pdf->setPaper('letter','landscape');
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $canvas->page_text(38,580,"Página {PAGE_NUM} de {PAGE_COUNT}",null,8,array(0,0,0));
//            $canvas->text(665,580,'PSAI-PN06-F01 Rev. 01',null,8);
//        $canvas->image('data:image/png;charset=binary;base64,'.$barcode,355,580,100,16);
        return $pdf->stream('solicitud')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
    }
}

