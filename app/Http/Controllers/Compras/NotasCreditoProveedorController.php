<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TiposRelacionesCfdi;
use App\Http\Models\Compras\FacturasProveedores;
use App\Http\Models\Compras\NotasCreditoProveedor;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\MetodosPago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use XmlParser;
use DB;
use Illuminate\Support\Facades\Cache;

class NotasCreditoProveedorController extends ControllerBase
{
	public function __construct(NotasCreditoProveedor $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {
        $facturas = [];
        if($entity && $entity->version_sat == "3.2")
        {
            $facturas = FacturasProveedores::select('id_factura_proveedor as id',db::raw("concat(serie_factura,folio_factura) as text"))->where('version_sat','3.2')->where('fk_id_socio_negocio',$entity->fk_id_socio_negocio)->pluck('text','id')->prepend('...',0);
        }
        return [
            'proveedores' => SociosNegocio::where('activo','t')->where('fk_id_tipo_socio_compra',3)->pluck('nombre_comercial','id_socio_negocio'),
            'sucursales' => Sucursales::where('activo','t')->pluck('sucursal','id_sucursal'),
            'relaciones' => TiposRelacionesCfdi::select(db::raw("concat('(',tipo_relacion,') ',descripcion) as text"),'id_sat_tipo_relacion')->where('activo',1)->where('nota_credito',1)->pluck('text','id_sat_tipo_relacion')->prepend('...',0),
            'js_facturas' => Crypt::encryptString('"select":["id_factura_proveedor","serie_factura","folio_factura"], "conditions":[{"where":["version_sat","3.2"]},{"where":["fk_id_socio_negocio",$fk_id_socio_negocio]}]'),
            'facturas' => $facturas,
            'js_relacionadas' => Crypt::encryptString('"select":["id_factura_proveedor","serie_factura","folio_factura","uuid"],"conditions":[{"where":["fk_id_estatus_factura",1]},{"whereIn":["uuid",$uuid]}]'),
            'js_tiporelacion' => Crypt::encryptString('"select":["id_sat_tipo_relacion","descripcion"],"conditions":[{"where":["tipo_relacion","$tipo_relacion"]}]'),
        ];
    }

    public function store(Request $request, $company, $compact = true)
    {
        # ¿Usuario tiene permiso para crear?
        //		$this->authorize('create', $this->entity);

        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);
        $fileName = $request->fk_id_socio_negocio."-".$request->uuid;
        $xml_save = Storage::disk('notas_proveedor')
            ->put($company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName.".xml",file_get_contents($request->archivo_xml_hidden->getRealPath()));
        $pdf_save = Storage::disk('notas_proveedor')
            ->put($company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName.".pdf",file_get_contents($request->archivo_pdf_hidden->getRealPath()));

        $request->request->set('archivo_xml',$company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName.".xml",file_get_contents($request->archivo_xml_hidden->getRealPath()));
        $request->request->set('archivo_pdf',$company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName.".pdf",file_get_contents($request->archivo_pdf_hidden->getRealPath()));

        $xml = simplexml_load_file($request->archivo_xml_hidden->getRealPath());
        $arrayData = xmlToArray($xml);
        if($request->version_sat == "3.3"){
            $request->request->set('serie_factura',isset($arrayData['Comprobante']['@Serie']) ? $arrayData['Comprobante']['@Serie'] : null);
            $request->request->set('fecha_factura',$arrayData['Comprobante']['@Fecha']);
            $request->request->set('fk_id_forma_pago',FormasPago::where('forma_pago', 'LIKE', $arrayData['Comprobante']['@FormaPago'])->first()->id_forma_pago);
            $request->request->set('total',$arrayData['Comprobante']['@Total']);
            $request->request->set('iva',$arrayData['Comprobante']['cfdi:Impuestos']['@TotalImpuestosTrasladados']);
            $request->request->set('subtotal',$arrayData['Comprobante']['@SubTotal']);
            $request->request->set('fk_id_moneda',Monedas::where('moneda', 'LIKE', $arrayData['Comprobante']['@Moneda'])->first()->id_moneda);
            $request->request->set('fk_id_metodo_pago',MetodosPago::where('metodo_pago', 'ILIKE', $arrayData['Comprobante']['@MetodoPago'])->first()->id_metodo_pago);
            $request->request->set('folio_factura',isset($arrayData['Comprobante']['@Folio']) ? $arrayData['Comprobante']['@Folio'] : null);
        }else if($request->version_sat == "3.2"){
            $request->request->set('serie_factura',isset($arrayData['Comprobante']['@serie']) ? $arrayData['Comprobante']['@serie'] : null);
            $request->request->set('fecha_factura',$arrayData['Comprobante']['@fecha']);
            $request->request->set('fk_id_forma_pago',MetodosPago::where('descripcion', 'ILIKE', "%".utf8_decode($arrayData['Comprobante']['@formaDePago'])."%")->first()->id_metodos_pago);
            $request->request->set('total',$arrayData['Comprobante']['@total']);
            $request->request->set('iva',$arrayData['Comprobante']['cfdi:Impuestos']['@totalImpuestosTrasladados']);
            $request->request->set('subtotal',$arrayData['Comprobante']['@subTotal']);
            $request->request->set('fk_id_moneda',Monedas::where('moneda', 'LIKE', $arrayData['Comprobante']['@Moneda'])->first()->id_moneda);
            $request->request->set('fk_id_metodo_pago',FormasPago::where('forma_pago', 'ILIKE', "%".$arrayData['Comprobante']['@metodoDePago']."%")->first()->id_forma_pago);
            $request->request->set('folio_factura',isset($arrayData['Comprobante']['@folio']) ? $arrayData['Comprobante']['@folio'] : null);
        }
        $request->request->set('fk_id_estatus_nota',1);
        $return = parent::store($request, $company, $compact);
        return $return['redirect'];
    }

    public function destroy(Request $request, $company, $idOrIds, $attributes = [])
    {
        if (!is_array($idOrIds)) {
            $isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
                ->update(['fk_id_estatus_factura' => 3,
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
                ->update(['fk_id_estatus_factura' => 3,
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

    public function parseXML($company,Request $request)
    {
        try{
            $xml = simplexml_load_file($request->file('file')->getRealPath());
            $arrayData = xmlToArray($xml);
            return validarRequerimientosCFDI($arrayData, $request->fk_id_socio_negocio, $company, "E");
        }catch (\Exception $e){
            return response()->json([
                'estatus' => -2,
                'resultado' => "No se pudo leer el XML porque tiene un formato incorrecto",
            ]);
        }
    }

}