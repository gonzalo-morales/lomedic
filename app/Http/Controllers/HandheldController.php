<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Inventarios;
use App\Http\Models\Inventarios\InventariosDetalle;
use App\Http\Models\Inventarios\SolicitudesSalida;
use App\Http\Models\Inventarios\SolicitudesSalidaDetalle;
use App\Http\Models\Inventarios\Ubicaciones;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Inventarios\SolicitudesDetalleSurtido;
use App\Http\Models\Inventarios\Upcs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;

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
	public function solicitudes($company)
	{
		return view('handheld.solicitudes', [
			# solicitudes con tipo de captura handheld
			'solicitudes' => SolicitudesSalidaDetalle::whereHas('empleados',function ($q){
				$q->where('fk_id_empleado',Auth::user()->fk_id_empleado)->with('pedidos');
			})->get(),
		]);
		// dd($solicitudes);
	}

	public function solicitud(Request $request, $company, SolicitudesSalidaDetalle $solicitud)
	{
		// dd($solicitud);
		return view('handheld.solicitud', [
			'solicitud' => $solicitud,
			'codigo_barras_js' => Crypt::encryptString('"conditions": [{"where": ["upc", "$upc"]}], "only": ["descripcion"]'),
		]);
	}

	public function solicitud_detalle_store(Request $request, $company)
	{
		// dd($request->all());
		SolicitudesDetalleSurtido::create( $request->all() );
		return redirect(companyRoute('handheld.solicitudes', ['id' => $request->fk_id_solicitud]))->with('message', 'Registro almacenado.');
	}
}