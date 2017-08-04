<?php

namespace App\Http\Controllers\Servicios;

use App\Http\Models\Servicios\Vehiculos;
use App\Http\Models\Administracion\VehiculosMarcas as Marcas;
use App\Http\Models\Administracion\VehiculosModelos as Modelos;
use App\Http\Models\Administracion\Tipocombustible as Combustible;
use App\Http\Models\Administracion\Sucursales as Sucursales;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Logs;
use Auth;
use Response;

class VehiculosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Vehiculos $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company)
    {
        // $vehiculosMarcas    = new Marcas();
        return view(Route::currentRouteName(), [
            'entity'    => $this->entity_name,
            'company'   => $company,
            'data'      => $this->entity->all()->where('eliminar','0'),
            // 'marcas'    => $this->vehiculosMarcas->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company)
    {
        $vehiculosMarcas    = new Marcas();
        $vehiculosModelos   = new Modelos();
        $combustible        = new Combustible();
        $sucursales         = new Sucursales();
        return view(Route::currentRouteName(), [
            'entity'        => $this->entity_name,
            'company'       => $company,
            'marcas'        => $vehiculosMarcas->where('activo', '=', '1')->where('eliminar','=', '0')->orderBy('marca')->get(),
            'modelos'       => $vehiculosModelos->where('activo', '=', '1')->where('eliminar','=', '0')->orderBy('modelo')->get(),
            'combustibles'  => $combustible->where('activo', '=', '1')->where('eliminar','=', '0')->orderBy('combustible')->get(),
            'sucursales'    => $sucursales->where('activo', '=', '1')->where('eliminar','=', '0')->orderBy('nombre_sucursal')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $company)
    {
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);
        // return $this->entity->fill($request->all());
        $created = $this->entity->create([
            'fk_id_marca'                => $request->marca,
			'fk_id_modelo'               => $request->modelos,
			'numero_serie'               => $request->numeroSerie,
			'modelo'                     => $request->modelo,
			'placa'                      => $request->placa,
			'capacidad_tanque'           => $request->capacidad,
			'rendimiento'                => $request->rendimiento,
			'lineas_tanque'              => $request->lineasPorTanque,
			'litros_linea'               => $request->litrosPorLinea,
			'no_tarjeta'                 => $request->numeroTarjeta,
			'fk_id_combustible'          => $request->combustible,
			'iave'                       => $request->iave,
			'folio'                      => $request->folioChecklist,
			'fk_id_sucursal'             => $request->sucursal,
			'fk_id_usuario_captura'      => Auth::id(),
			'fk_id_usuario_modificacion' => Auth::id(),
			'activo'                     => $request->activo,
		]);
        if($created){
            Logs::createLog($this->entity->getTable(), $company, $created->id_vehiculo, 'crear','Registro creado');
        }else{
            Logs::createLog($this->entity->getTable(), $company, null, 'crear','Error al crear');
        }
        # Redirigimos a index
        return redirect(companyRoute('index'));
    }

    /**
     * Display the specified resource
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($company, $id)
    {
        // Logs::createLog($this->entity->getTable(),$company,$id,'ver',null);
        return view (Route::currentRouteName(), [
            'entity'    => $this->entity_name,
            'company'   => $company,
            'data'      => $this->entity->findOrFail($id),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($company, $id)
    {
        $vehiculosMarcas    = new Marcas();
        $vehiculosModelos   = new Modelos();
        $combustible        = new Combustible();
        $sucursales         = new Sucursales();

        return view (Route::currentRouteName(), [
            'entity'        => $this->entity_name,
            'company'       => $company,
            'data'          => $this->entity->findOrFail($id),
            'marcas'        => $vehiculosMarcas->where('activo', '=', '1')->where('eliminar','=', '0')->orderBy('marca')->get(),
            'modelos'       => $vehiculosModelos->where('fk_id_marca',$this->entity->find($id)->fk_id_marca)
                               ->where('activo', '=', '1')->where('eliminar','=', '0')->orderBy('modelo')->get(),
            'combustibles'  => $combustible->where('activo', '=', '1')->where('eliminar','=', '0')->orderBy('combustible')->get(),
            'sucursales'    => $sucursales->where('activo', '=', '1')->where('eliminar','=', '0')->orderBy('nombre_sucursal')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer	$id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company, $id)
    {
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

        $entity = $this->entity->findOrFail($id);
        $entity->fill([
            'fk_id_marca'                => $request->marca,
			'fk_id_modelo'               => $request->modelos,
			'numero_serie'               => $request->numeroSerie,
			'modelo'                     => $request->modelo,
			'placa'                      => $request->placa,
			'capacidad_tanque'           => $request->capacidad,
			'rendimiento'                => $request->rendimiento,
			'lineas_tanque'              => $request->lineasPorTanque,
			'litros_linea'               => $request->litrosPorLinea,
			'no_tarjeta'                 => $request->numeroTarjeta,
			'fk_id_combustible'          => $request->combustible,
			'iave'                       => $request->iave,
			'folio'                      => $request->folioChecklist,
			'fk_id_sucursal'             => $request->sucursal,
			'fk_id_usuario_captura'      => Auth::id(),
			'fk_id_usuario_modificacion' => Auth::id(),
			'activo'                     => $request->activo,
		]);
        if($entity->save()){
            Logs::createLog($this->entity->getTable(),$company,$id,'editar','Registro actualizado');
        }else{
            Logs::createLog($this->entity->getTable(),$company,$id,'editar','Error al editar');
        }
        # Redirigimos a index
        return redirect(companyRoute('index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer 	$id
     * @return \Illuminate\Http\Response
     */
    public function destroy($company, $id)
    {
        $entity = $this->entity->findOrFail($id);
        $entity->eliminar = 't';
        if($entity->save()){
            Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');
        }else{
            Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al editar');
        }

        # Redirigimos a index
        return redirect(companyRoute('index'));
    }

    /**
     * Obtiene los modelos de vehiculos relacionados a una marca de vehiculo
     * @param  integer $idMarca identificador de la marca del vehiculo
     * @param  string $company nombre de la campañia seleccionada
     * @return array de json
     */
    public function getModelos($company, $idMarca){
        $modelos = Modelos::where('fk_id_marca',$idMarca)->where('activo', '1')->where('eliminar', '0')->get();

        return Response::json($modelos->toArray());
    }
}
