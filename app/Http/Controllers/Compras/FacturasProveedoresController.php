<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Compras\DetalleFacturasProveedores;
use App\Http\Models\Compras\FacturasProveedores;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\MetodosPago;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use XmlParser;
use DB;
use Illuminate\Support\Facades\Cache;

class FacturasProveedoresController extends ControllerBase
{
	public function __construct(FacturasProveedores $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {
        return [
            'proveedores' => SociosNegocio::where('activo','t')->where('fk_id_tipo_socio_compra',3)->pluck('nombre_comercial','id_socio_negocio'),
            'sucursales' => Sucursales::where('activo','t')->pluck('sucursal','id_sucursal'),
            'js_comprador' => Crypt::encryptString('"select": ["nombre","apellido_paterno","apellido_materno"], "conditions": [{"where": ["activo","1"]}], "whereHas": [{"ejecutivocompra":{"where":["id_socio_negocio","$id_socio_negocio"]}}]')
        ];
    }

    public function store(Request $request, $company)
    {
        # ¿Usuario tiene permiso para crear?
        //		$this->authorize('create', $this->entity);

        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);

        $fileName = $request->fk_id_socio_negocio."-".$request->uuid;
        $xml_save = Storage::disk('factura_proveedor')
            ->put($company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName.".xml",file_get_contents($request->archivo_xml_hidden->getRealPath()));
        $pdf_save = Storage::disk('factura_proveedor')
            ->put($company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName.".pdf",file_get_contents($request->archivo_pdf_hidden->getRealPath()));

        $request->request->set('archivo_xml',$company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName.".xml",file_get_contents($request->archivo_xml_hidden->getRealPath()));
        $request->request->set('archivo_pdf',$company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName.".pdf",file_get_contents($request->archivo_pdf_hidden->getRealPath()));

        $xml = simplexml_load_file($request->archivo_xml_hidden->getRealPath());
        $arrayData = xmlToArray($xml);
//        dd($arrayData);
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
        $request->request->set('fk_id_estatus_factura',1);

        DB::beginTransaction();
        $isSuccess = $this->entity->create($request->all());
        if ($isSuccess && $xml_save && $pdf_save) {
            # Si tienes relaciones
            foreach ($request->productos as $producto) {
                $producto['fk_id_orden_compra'] = !empty($producto['fk_id_orden_compra']) ? $producto['fk_id_orden_compra'] : null;
                $isSuccess->detalle_facturas_proveedores()->save(new DetalleFacturasProveedores($producto));
            }
            DB::commit();

            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();

            $this->log('store', $isSuccess->id_factura_proveedor);
            return $this->redirect('store');
        } else {
            DB::rollBack();
            $this->log('error_store');
            return $this->redirect('error_store');
        }
    }

    public function update(Request $request, $company, $id)
    {
        # ¿Usuario tiene permiso para actualizar?
        //		$this->authorize('update', $this->entity);

        # Validamos request, si falla regresamos atras
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);

        DB::beginTransaction();
        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        if ($entity->save()) {

            # Si tienes relaciones
            foreach ($request->producto as $key => $producto){
                $detalle = $entity
                    ->findOrFail($id)
                    ->detalle_facturas_proveedores()
                    ->where('id_detalle_factura_proveedor',$key)
                    ->first();
                $detalle->fill($producto);
                $detalle->save();
            }
            DB::commit();

            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();

            $this->log('update', $id);
            return $this->redirect('update');
        } else {
            DB::rollBack();
            $this->log('error_update', $id);
            return $this->redirect('error_update');
        }
    }

    public function destroy(Request $request, $company, $idOrIds)
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
            return validarRequerimientosCFDI($arrayData, $request->fk_id_socio_negocio, $company, "I");
        }catch (\Exception $e){
            return response()->json([
                'estatus' => -2,
                'resultado' => "No se pudo leer el XML porque tiene un formato incorrecto",
            ]);
        }
    }
}