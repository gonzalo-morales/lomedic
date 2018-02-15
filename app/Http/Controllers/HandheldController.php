<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Inventarios;
use App\Http\Models\Inventarios\InventariosDetalle;
use App\Http\Models\Inventarios\MovimientoAlmacen;
use App\Http\Models\Inventarios\MovimientoAlmacenDetalle;
use App\Http\Models\Inventarios\SolicitudesSalida;
use App\Http\Models\Inventarios\SolicitudesSalidaDetalle;
use App\Http\Models\Inventarios\Ubicaciones;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Inventarios\SolicitudesDetalleSurtido;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Stock;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Inventarios\Entradas;
use App\Http\Models\Inventarios\EntradaDetalle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class HandheldController extends Controller
{
	public function inventarios($company)
	{
		return view('handheld.inventarios', [
			# Inventarios con tipo de captura handheld
			'inventarios' => Inventarios::with('almacen:id_almacen,almacen')->where('tipo_captura', 2)->get()
		]);
	}

	public function inventario(Request $request, $company, Inventarios $inventario)
	{
		if( $request->has(['fk_id_ubicacion','codigo_barras']) ) {
			$ubicacion = Ubicaciones::select(['id_ubicacion','codigo_barras', 'rack', 'ubicacion', 'posicion', 'nivel'])->where('id_ubicacion', $request->fk_id_ubicacion)->first();
			$upc = Upcs::select(['upc','descripcion'])->where('upc', $request->codigo_barras)->first();
		}
		return view('handheld.inventario', [
			'inventario' => $inventario,
			'ubicacion' => $ubicacion ?? [],
			'upc' => $upc ?? [],
			'ubicacion_js' => Crypt::encryptString('"conditions": [{"where": ["codigo_barras", "$codigo_barras"]}, {"where": ["fk_id_almacen", "$fk_id_almacen"]}, {"where": ["activo","true"]}], "only": ["id_ubicacion","nomenclatura"]'),
			'codigo_barras_js' => Crypt::encryptString('"conditions": [{"where": ["upc", "$upc"]}], "only": ["descripcion"]'),
		]);
	}

	public function inventario_detalle(Request $request, $company)
	{
		return view('handheld.inventarios-inventario-detalle', [
			'previous' => $request
		]);
	}

	public function inventario_detalle_store(Request $request, $company)
	{
		InventariosDetalle::create( $request->all() );
		return redirect(companyRoute('handheld.inventarios-inventario', ['id' => $request->fk_id_inventario]))->with('message', 'Registro almacenado.');
	}

	//NANDO'S stuff
	/*Solicitudes*/
	public function solicitudes($company)
	{
		return view('handheld.solicitudes', [
			# solicitudes con tipo de captura handheld
			'solicitudes' => SolicitudesSalidaDetalle::whereHas('empleados',function ($q){
				$q->where('fk_id_empleado',Auth::user()->fk_id_empleado)->with('pedidos');
			})->where('falta_surtir', '>' , 0)->get(),
		]);
		// dd($solicitudes);
	}

	public function solicitud(Request $request, $company, SolicitudesSalidaDetalle $solicitud)
	{
		// dd($solicitud);
		return view('handheld.solicitud', [
			'solicitud' => $solicitud,
			'codigo_barras_js' => Crypt::encryptString('"conditions": [{"where": ["upc", "$upc"]}], "select": ["id_upc","upc"]'),
		]);
	}

	public function solicitud_detalle_store(Request $request, $company)
	{
		// dd($request->request);
		$id = $request->id_detalle;
		$detalle = SolicitudesSalidaDetalle::where('id_detalle',$id)->update([
			'cantidad_escaneada' => $request->cantidad_escaneada,
			'falta_surtir' => $request->falta_surtir,
		]);
		return redirect(companyRoute('handheld.solicitudes', ['id' => $request->fk_id_solicitud]))->with('message', 'Registro almacenado.');
	}

	//NANDO'S stuff
	/*Movimiento_almacen*/
	public function sucursales($company)
	{
		return view('handheld.sucursales', [
			# sucursales con tipo de captura handheld
			'sucursales'  => Sucursales::whereHas('empleados',function ($q){$q->where('id_empleado',Auth::user()->fk_id_empleado);})->get(),
		]);
		// dd($sucursales);
	}

	public function almacenes(Request $request,$company)
	{
		return view('handheld.almacenes', [
			# almacenes con tipo de captura handheld
			'sucursal' => $request,
		    'almacenes'  => Almacenes::where('activo',1)->where('fk_id_sucursal',$request->id)->get(),
		]);
		// dd($almacenes);
	}

	public function movimientos(Request $request,$company)
	{
		return view('handheld.movimientos', [
			#movimientos con tipo captura handheld
			'sucursal' => $request->id_sucursal,
			'almacen' => $request->id,
		    'stock'  => Stock::where('activo',1)->where('fk_id_almacen',$request->id)->with('upc:id_upc,upc,nombre_comercial,descripcion','almacen:id_almacen')->select('id_stock','fk_id_sku','fk_id_upc','lote','fecha_caducidad','stock','fk_id_ubicacion')->get(),
		]);
	}

	public function movimiento(Request $request, $company, Stock $movimiento)
	{
		// dd($movimiento);
		return view('handheld.movimiento', [
			'fk_id_sucursal' => $request->id_sucursal,
			'fk_id_almacen' => $request->id_almacen,
			'fk_id_usuario' => Auth::user()->id_usuario,
			'fecha_operacion' => Carbon::now(),
			'previous' => $movimiento->fk_id_almacen,
			// 'padre' => $movimiento->fk_id_
			'movimiento' => $movimiento,
			'ubicacion' => Ubicaciones::select(['id_ubicacion','ubicacion'])->where('fk_id_almacen', $request->id_almacen)->pluck('ubicacion', 'id_ubicacion')->prepend('Seleccione la ubicación',''),
			// 'upc' => Upcs::where('id_upc', $stock->fk_id_upc)->get(),
			# almacenes con tipo de captura handheld
			'codigo_barras_js' => Crypt::encryptString('"conditions": [{"where": ["upc", "$upc"]}], "select": ["id_upc","upc"]'),
		]);
		// dd($upc);
	}

	public function stock_movimiento_detalle_store(Request $request, $company)
	{
		$isSuccess = MovimientoAlmacen::create($request->all());
		if($isSuccess){
			$request->request->set('fk_id_movimiento',$isSuccess->id_movimiento);
			MovimientoAlmacenDetalle::create( $request->all() );
		}
		return redirect(companyRoute('handheld.movimientos', ['id' => $request->fk_id_almacen]))->with('message', 'Registro almacenado.');
	}

	//NANDO'S stuff
	/*Ordenes Compra*/
	public function ordenes($company)
	{
		return view('handheld.ordenes', [
			'ordenes' => Ordenes::whereHas('detalleOrdenes',function ($q){$q->whereRaw('cantidad - cantidad_recibida != 0');})->with('detalleOrdenes')->where('fk_id_estatus_orden', 1)->get(),
		]);
	}
	public function orden(Request $request, $company, ordenes $orden)
	{
	    $detalles = DetalleOrdenes::where('fk_id_documento',$orden->id_documento)->with('sku:id_sku,sku')->get();
		$skus = $detalles->map(function ($detalle){
			return $detalle->sku;
		});
		return view('handheld.orden-compra', [
			'orden' => $orden,
			'almacenes' => Almacenes::where('activo',1)->where('fk_id_sucursal',$orden->fk_id_sucursal)->pluck('almacen','id_almacen')->prepend('Seleccione el almacén','0'),
			'ubicaciones_js' => Crypt::encryptString('"conditions": [{"where": ["fk_id_almacen", "$almacen"]}], "select": ["id_ubicacion","ubicacion"]'),
			'fecha_entrada' => Carbon::now(),
			'skus' => $skus->pluck('sku','id_sku'),
			'codigo_barras_js' => Crypt::encryptString('"conditions": [{"where": ["fk_id_documento", "$orden"]},{"whereRaw":["cantidad - cantidad_recibida != 0"]},{"where": ["fk_id_sku","$id_sku"]}],"whereHas": [{"upc":{"where":["upc","ILIKE", "$upc"]}}], "select": ["fk_id_sku","fk_id_upc","cantidad","cantidad_recibida","id_documento_detalle","fk_id_proyecto","precio_unitario","fk_id_tipo_documento","fk_id_proyecto"]'),
		]);
	}
	public function entrada_detalle_store(Request $request, $company, $compact = false)
	{
		$isSuccess = Entradas::create($request->all());
		if($isSuccess){
			// EntradaDetalle::create( $request->all() );
			foreach ($request->datos_entradas as $detalle)
			{
				EntradaDetalle::create([
					'fk_id_documento' => $isSuccess->id_documento,
					'fk_id_sku' => $detalle["fk_id_sku"],
					'fk_id_upc' => $detalle["fk_id_upc"],
					'cantidad_surtida' => $detalle["cantidad_surtida"],
					'lote' => $detalle["lote"],
					'fecha_caducidad' => $detalle["fecha_caducidad"],
					'fk_id_linea' => $detalle["fk_id_linea"],
					'fk_id_proyecto' => $detalle["fk_id_proyecto"],
					'fk_id_tipo_documento_base' => $detalle["fk_id_tipo_documento_base"],
					'precio_unitario' => $detalle["precio_unitario"],
				]);
			}
		}
		return redirect(companyRoute('handheld.ordenes'))->with('message', 'Registro almacenado.');
	}
}