<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Compras\DetalleOfertas;
use App\Http\Models\Compras\Ofertas;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\Solicitudes;
use Milon\Barcode\DNS2D;
use Milon\Barcode\DNS1D;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\SociosNegocio\TiposEntrega;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;


class OfertasController extends ControllerBase
{
	public function __construct(Ofertas $entity)
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

	public function create($company, $attributes =[])
	{
	    !is_array($attributes) ? $id_solicitud = $attributes : $id_solicitud = 0;//Si tiene un id de solicitud
	    $clientes = SociosNegocio::where('activo', 1)->whereNotNull('fk_id_tipo_socio_venta')->pluck('nombre_comercial','id_socio_negocio');

        $attributes = ['dataview'=>[
            'companies' => Empresas::where('activo',1)->where('conexion','<>',$company)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
            'actual_company_id'=>Empresas::where('conexion','LIKE',$company)->first()->id_empresa,
            'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
            'clientes' => $clientes,
            'monedas'=>Monedas::where('activo',1)->select('id_moneda',DB::raw("concat(descripcion,' (',moneda,')') as moneda"))->pluck('moneda','id_moneda'),
            'proyectos' => Proyectos::where('activo',1)->pluck('proyecto','id_proyecto'),
            'unidadesmedidas'=>UnidadesMedidas::where('activo',1)->pluck('nombre','id_unidad_medida'),
            'solicitud' => Solicitudes::find($id_solicitud),
        ]];
        return parent::create($company,$attributes);
	}

	public function store(Request $request, $company)
	{
        # ¿Usuario tiene permiso para crear?
//		$this->authorize('create', $this->entity);
		# Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

		$request->request->set('fk_id_estatus_oferta',1);
		if(empty($request->fk_id_empresa)){
		    $request->request->set('fk_id_empresa',Empresas::where('conexion','LIKE',$company)->first()->id_empresa);
        }
        if(empty($request->descuento_oferta)){
		    $request->request->set('descuento_oferta',0);
        }
        $isSuccess = $this->entity->create($request->all());
		if ($isSuccess) {
			if(isset($request->_detalles)) {
				foreach ($request->_detalles as $detalle) {
                    if(empty($detalle['fk_id_upc'])){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(empty($detalle['fk_id_cliente'])){
                        $detalle['fk_id_cliente'] = null;
                    }
                    if(empty($detalle['fk_id_proyecto'])){
                        $detalle['fk_id_proyecto'] = null;
                    }
					$isSuccess->detalleOfertas()->save(new DetalleOfertas($detalle));
				}
			}
			if(isset($request->detalles)){
			    foreach ($request->detalles as $detalle){
                    if(empty($detalle['fk_id_upc'])){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(empty($detalle['fk_id_cliente'])){
                        $detalle['fk_id_cliente'] = null;
                    }
                    if(empty($detalle['fk_id_proyecto'])){
                        $detalle['fk_id_proyecto'] = null;
                    }
                    $isSuccess->detalleOfertas()->save(new DetalleOfertas($detalle));
                }
            }
            $this->log('store', $isSuccess->id_orden);
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
                'companies' => Empresas::where('activo',1)->where('conexion','<>',$company)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
                'actual_company_id'=>Empresas::where('conexion','LIKE',$company)->first()->id_empresa,
                'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
                'proveedores'=>$proveedores,
                'monedas'=>Monedas::where('activo',1)->select('id_moneda',DB::raw("concat(descripcion,' (',moneda,')') as moneda"))->pluck('moneda','id_moneda'),
                'proyectos' => Proyectos::where('activo',1)->pluck('proyecto','id_proyecto'),
                'unidadesmedidas'=>UnidadesMedidas::where('activo',1)->pluck('nombre','id_unidad_medida'),
                'company' => $company
			]];
		return parent::show($company,$id,$attributes);
	}

	public function edit($company,$id,$attributes = [])
	{
	    $clientes = SociosNegocio::where('activo', 1)->whereNotNull('fk_id_tipo_socio_venta')->pluck('nombre_comercial','id_socio_negocio');
	    $proveedores = SociosNegocio::where('activo', 1)->whereNotNull('fk_id_tipo_socio_compra')->pluck('nombre_comercial','id_socio_negocio');
		$attributes = $attributes+['dataview'=>[
                'companies' => Empresas::where('activo',1)->where('conexion','<>',$company)->where('conexion','<>','corporativo')->pluck('nombre_comercial','id_empresa'),
                'actual_company_id'=>Empresas::where('conexion','LIKE',$company)->first()->id_empresa,
                'sucursales' => Sucursales::where('activo',1)->pluck('sucursal','id_sucursal'),
                'clientes' => $clientes,
                'proveedores' => $proveedores,
                'monedas'=>Monedas::where('activo',1)->select('id_moneda',DB::raw("concat(descripcion,' (',moneda,')') as moneda"))->pluck('moneda','id_moneda'),
                'proyectos' => Proyectos::where('activo',1)->pluck('proyecto','id_proyecto'),
                'unidadesmedidas'=>UnidadesMedidas::where('activo',1)->pluck('nombre','id_unidad_medida'),
			]];
		return parent::edit($company, $id, $attributes);
	}

	public function update(Request $request, $company, $id)
	{
		# ¿Usuario tiene permiso para actualizar?
//		$this->authorize('update', $this->entity);

		# Validamos request, si falla regresamos atras
		$this->validate($request, $this->entity->rules);
		$entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		if ($entity->save()) {
			if(isset($request->detalles)) {
				foreach ($request->detalles as $detalle) {
						$oferta_detalle = $entity
							->findOrFail($id)
							->detalleOfertas()
							->where('id_oferta_detalle', $detalle['id_oferta_detalle'])
							->first();
						$oferta_detalle->fill($detalle);
						$oferta_detalle->save();
				}
			}
			if(isset($request->_detalles)){
				foreach ($request->_detalles as $detalle){
                    if(empty($detalle['fk_id_upc'])){
                        $detalle['fk_id_upc'] = null;
                    }
                    if(empty($detalle['fk_id_cliente'])){
                        $detalle['fk_id_cliente'] = null;
                    }
                    if(empty($detalle['fk_id_proyecto'])){
                        $detalle['fk_id_proyecto'] = null;
                    }
					$entity->detalleOfertas()->save(new DetalleOfertas($detalle));
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
        if(!isset($request->ids)){
            if (!is_array($idOrIds)) {

                $isSuccess = $this->entity->where($this->entity->getKeyName(), $idOrIds)
                    ->update(['fk_id_estatus_oferta' => 3]);
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
                    ->update(['fk_id_estatus_oferta' => 3]);
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
        }else{
            DetalleOfertas::whereIn('id_oferta_detalle', $request->ids)->update(['cerrado' => 't']);
            return 'Eliminado con éxito';
        }
	}

    public function impress($company,$id)
    {
        $oferta = Ofertas::find($id);

        $subtotal = 0;
        $iva = 0;
        $total = 0;

        foreach ($oferta->DetalleOfertas()->where('cerrado','f')->get() as $detalle)
        {
            $subtotal += $detalle->precio_unitario * $detalle->cantidad;
            $iva += (($detalle->precio_unitario*$detalle->cantidad)*$detalle->impuesto->porcentaje)/100;
            $total += $detalle->total;
        }
        $total = number_format($total,2,'.',',');

        $barcode = DNS1D::getBarcodePNG($oferta->id_oferta,'EAN8');
        $qr = DNS2D::getBarcodePNG(asset(companyAction('show',['id'=>$oferta->id_oferta])), "QRCODE");

        $pdf = PDF::loadView(currentRouteName('compras.ofertas.imprimir'),[
            'oferta' => $oferta,
//            'detalles' => $detalles,
            'subtotal' => $subtotal,
            'iva' => $iva,
            'total' => $total,
            'total_letra' => num2letras($total),
            'barcode' => $barcode,
            'qr' => $qr
        ]);

        $pdf->setPaper('letter','landscape');
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();
        $canvas = $dom_pdf->get_canvas();
        $canvas->page_text(38,580,"Página {PAGE_NUM} de {PAGE_COUNT}",null,8,array(0,0,0));
//        $canvas->text(665,580,'PSAI-PN06-F01 Rev. 01',null,8);
//        $canvas->image('data:image/png;charset=binary;base64,'.$barcode,355,580,100,16);

        return $pdf->stream('orden')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
    }

    public function getProveedores($company){
        $proveedores = SociosNegocio::where('activo', 1)->whereNotNull('fk_id_tipo_socio_compra')->select('id_socio_negocio as id','nombre_comercial as text','tiempo_entrega')->get();
	    return Response::json($proveedores);
    }
}
