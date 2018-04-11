<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Solicitudes;
use App\Http\Models\Compras\Ofertas;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\CondicionesAutorizacion;
use App\Http\Models\Compras\Autorizaciones;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\SociosNegocio\ProductosSociosNegocio;
use Carbon\Carbon;
use function foo\func;
use Milon\Barcode\DNS2D;
use Milon\Barcode\DNS1D;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\TiposEntrega;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class OrdenesController extends ControllerBase
{
	public function __construct()
	{
	    $this->entity = new Ordenes;
	}

	public function getDataView($entity = null)
    {
        if (Auth::check())
        {
            // The user is logged in...
            $user = Auth::id();
        }
        switch (\request('tipo_documento')){
            case 1:
                $documento = Solicitudes::find(\request('id'));
                $detalles_documento = $documento->detalle()->where('cerrado',0)->select('*','fk_id_documento','importe as total_producto','impuesto_total as total_impuesto')->get();
                break;
            case 2:
                $documento = Ofertas::find(\request('id'));
                $detalles_documento = $documento->detalle()->where('cerrado',0)->select('*','fk_id_documento')->get();
                break;
            default:
                $documento = null;
                $detalles_documento = null;
                break;
        }
        $proveedores = SociosNegocio::where('activo',1)->where('fk_id_tipo_socio_compra',3)->whereHas('empresas',function ($empresa){
            $empresa->where('id_empresa',dataCompany()->id_empresa)->where('eliminar','f');
        })->pluck('nombre_comercial','id_socio_negocio');
        return [
            'companies' => Empresas::where('activo',1)->where('conexion','<>',\request()->company)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
            'documento' =>$documento,
            'detalles_documento'=>$detalles_documento,
            'tipo_documento' => \request('tipo_documento'),
            'sucursales' 	 => Sucursales::isActivo()->whereHas('usuario', function($u) use ($user){
                $u->where('fk_id_usuario', $user);
            })->whereHas('empresas',function($e){
                $e->where('fk_id_empresa',dataCompany()->id_empresa);
            })->pluck('sucursal','id_sucursal'),
            'proveedores' => $proveedores ?? '',
            'tiposEntrega' => TiposEntrega::where('activo',1)->pluck('tipo_entrega','id_tipo_entrega'),
            'condicionesPago' => CondicionesPago::where('activo',1)->pluck('condicion_pago','id_condicion_pago'),
//            'js_tiempo_entrega' => Crypt::encryptString('"selectRaw":["max(tiempo_entrega) as tiempo_entrega"],"conditions":[{"whereRaw":["(fk_id_socio_negocio IS NULL OR fk_id_socio_negocio = \'$fk_id_socio_negocio\') AND fk_id_sku = \'$fk_id_sku\' AND ($fk_id_upc IS NULL OR fk_id_upc = $fk_id_upc)"]}]'),
            'js_tiempo_entrega' => Crypt::encryptString('
                "selectRaw": ["max(tiempo_entrega) as tiempo_entrega"],
                "withFunction": [{
                "productos": {
                    "selectRaw": ["max(tiempo_entrega) as tiempo_entrega"],
                    "whereRaw": ["($fk_id_socio_negocio = NULL OR fk_id_socio_negocio = $fk_id_socio_negocio) AND fk_id_sku = $fk_id_sku AND ($fk_id_upc = NULL OR fk_id_upc = $fk_id_upc)"],
                    "groupBy": ["fk_id_socio_negocio","fk_id_sku","fk_id_upc"]
                }
                }],
                "groupBy": ["fk_id_socio_negocio","fk_id_sku","fk_id_upc"]'),
            'js_proyectos'=>Crypt::encryptString('
                "select":["id_proyecto as id","proyecto as text"],
                "whereHas": [{
                    "productos": {
                        "cwhereHas": [{
                            "claveClienteProducto": [{
                                "whereRaw": "($fk_id_sku = NULL OR fk_id_sku = $fk_id_sku) AND ($fk_id_upc = NULL OR fk_id_upc = $fk_id_upc)"
                            }]
                        }]
                    }
                }]
            '),
            'condiciones'=>Usuarios::find(Auth::id())->condiciones->where('fk_id_tipo_documento',3)->where('activo',1),
        ];

    }

    public function index($company, $attributes=[]){
		$attributes = $attributes+['dataview'=>[
				'detalles' => $this->entity->detalle->where('cerrado',false),
				'estatus' => 1,
			]];
			return parent::index($company,$attributes);
	}

	public function create($company, $attributes =[])
	{
	    $attributes = [];
	    $documento = $this->getDataView()['documento'] ?? null;
        $data = $this->entity->getColumnsDefaultsValues();
        if(!empty($documento) && $documento->fk_id_tipo_documento == 2){
            $data['fk_id_empresa'] = $documento->fk_id_empresa;
            $data['fk_id_sucursal'] = $documento->fk_id_sucursal;
            $data['fk_id_socio_negocio'] = $documento->fk_id_proveedor;
            $attributes['data'] = $data;
        }
        return parent::create($company,$attributes);
	}

	public function store(Request $request, $company, $compact = false)
	{
        $request->request->set('fecha_creacion',Carbon::now()->toDateString());
        $request->request->set('fk_id_estatus_orden',1);
        $request->request->set('fk_id_empresa',Empresas::where('conexion','LIKE',$company)->first()->id_empresa);
        if(!empty($request->importacion)){
            $request->request->set('importacion','t');
        }

        if(empty($request->tiempo_entrega) || empty($request->fecha_estimada_entrega)){
            $request->request->set('tiempo_entrega',null);
            $request->request->set('fecha_estimada_entrega',null);
        }

        $request->request->set('fecha_cancelacion',null);
        $request->request->set('motivo_cancelacion',null);

	    return parent::store($request,$company,$compact);
	}

    public function impress($company,$id)
    {
        $orden = Ordenes::find($id);

        $barcode = DNS1D::getBarcodePNG($orden->id_documento,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$orden->id_documento])), "QRCODE");

        $pdf = PDF::loadView(currentRouteName('compras.ordenes.imprimir'),[
            'orden' => $orden,
            'barcode' => $barcode,
            'qr' => $qr
        ]);

        $pdf->setPaper('letter','landscape');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(38,580,"Página {PAGE_NUM} de {PAGE_COUNT}",null,8,array(0,0,0));
        $canvas->text(665,580,'PSAI-PN06-F01 Rev. 01',null,8);
//        $canvas->image('data:image/png;charset=binary;base64,'.$barcode,355,580,100,16);

        return $pdf->stream('orden')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
    }

    public function getProveedores($company){
	    $id_empresa = \request()->fk_id_empresa > 0 ? \request()->fk_id_empresa : Empresas::where('conexion',$company)->first()->id_empresa;
	    $proveedores = SociosNegocio::where('activo',1)->whereHas('empresas',function ($q) use ($id_empresa){
            $q->where('id_empresa',$id_empresa);
        })->where('fk_id_tipo_socio_compra',3)->select('id_socio_negocio as id','nombre_comercial as text','tiempo_entrega')->get();
	    return Response::json($proveedores);
    }

    public function getDetallesOrden()
    {
        $result = DetalleOrdenes::join('inv_cat_skus','com_det_ordenes.fk_id_sku','=','inv_cat_skus.id_sku')
            ->leftJoin(getSchema().'.inv_cat_upcs',function($join){
                $join->on('com_det_ordenes.fk_id_upc','=',getSchema().'.inv_cat_upcs.id_upc');
            })
            ->where('fk_id_documento','=',$_POST['id_orden'])
            ->select(db::raw("concat(inv_cat_skus.sku, ' - ' ,getSchema().inv_cat_upcs.upc) as value"),'com_det_ordenes.id_documento_detalle as id')
            ->get();

        return $result;
    }

    public function destroy(Request $request, $company, $idOrIds, $attributes = [])
    {
        if (!is_array($idOrIds)) {
            $isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
                ->update(['fk_id_estatus_orden' => 3,
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
}