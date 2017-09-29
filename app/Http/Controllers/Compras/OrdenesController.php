<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Proveedores;
use App\Http\Models\Administracion\Unidadesmedidas;
use App\Http\Models\Compras\DetalleSolicitudes;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\Solicitudes;
use App\Http\Models\Finanzas\Impuestos;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\RecursosHumanos\Empleados;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenesController extends ControllerBase
{
	public function __construct(Ordenes $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView()
	{
		return [
			'proveedores' => Proveedores::select(['nombre','id_proveedor'])->pluck('nombre','id_proveedor'),
		];
	}


	public function index($company, $attributes = [])
	{
		$attributes = ['where'=>[]];
		return parent::index($company, $attributes);
	}

	public function create($company, $attributes =[])
	{

		// dump( $this->getDataView() );
		// die();

		$attributes = $attributes+['dataview'=>[
				'sucursalesempleado' => $this->entity->first()
					->empleado()->first()
					->sucursales()->get()
					->pluck('nombre_sucursal','id_sucursal'),
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
					->pluck('nombre','id_unidad_medida')
			]];
		return parent::create($company, [
			'dataview' => $this->getDataView()
		]);
		// return parent::create($company,$attributes);
	}

	public function store(Request $request, $company)
	{
		# ¿Usuario tiene permiso para crear?
		$this->authorize('create', $this->entity);

		# Validamos request, si falla regresamos pagina
		$this->validate($request, $this->entity->rules);

		$request->request->set('fecha_creacion',DB::raw('now()'));
		if($request->fk_id_estatus_solicitud === 3)//Si es cancelado
		{$request->request->set('fecha_cancelacion',DB::raw('now'));}

		$request->request
			->set('fk_id_departamento',Empleados::where('id_empleado',$request->fk_id_solicitante)
				->first()
				->fk_id_departamento);
		$request->request->set('fk_id_estatus_solicitud',1);
		$isSuccess = $this->entity->create($request->all());
		if ($isSuccess) {
			if(isset($request->_detalles)) {
				foreach ($request->_detalles as $detalle) {
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
			]];
		return parent::show($company,$id,$attributes);
	}

	public function edit($company,$id,$attributes = [])
	{
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
				->where('activo',1)
				->get()
				->pluck('proyecto','id_proyecto'),
			'unidadesmedidas' => Unidadesmedidas::select('nombre','id_unidad_medida')
				->where('activo',1)
				->get()
				->pluck('nombre','id_unidad_medida')
			]];
		return parent::edit($company, $id, $attributes);
	}

	public function update(Request $request, $company, $id)
	{
//        dd($request->request);
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
						$solicitud_detalle->fill($detalle);
						$solicitud_detalle->save();
				}
			}
			if(isset($request->_detalles)){
				foreach ($request->_detalles as $detalle){
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
					'motivo_cancelacion'=>$request->motivo_cancelacion,
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

	public function print_solicitud($company,$id)
	{
//        $numero = 123456.50;

//        dd(\NumeroALetras::convertir(number_format($numero,2,',',''),'pesos'));
//        dd(PDF::loadView(currentRouteName('imprimir')));
		$pdf = PDF::loadView(currentRouteName('imprimir'));
		$pdf->setPaper('letter','landscape');


		return $pdf->stream('solicitud')->header('Content-Type',"application/pdf");
//        return view(currentRouteName('imprimir'));
	}
}
