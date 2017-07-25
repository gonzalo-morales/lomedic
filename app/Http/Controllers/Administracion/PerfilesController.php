<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Perfiles;
use App\Http\Models\Administracion\Modulos;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Administracion\Empresas;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
class PerfilesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Perfiles $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
        $this->company = request()->company;
        $this->users = Usuarios::all();
        $this->modules = Modulos::all();
        $this->empresas = Empresas::all();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'data' => $this->entity->all()->where('eliminar', '=','0'),
            'company' => $this->company,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $this->company,
            'users' => $this->users,
            'modules' => $this->modules,
            'empresas' => $this->empresas
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

//        dd( $request->all() );

        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

        $this->entity->fill($request->all());
        $this->entity->fk_id_usuario_crea = Auth::id();
        $this->entity->save();

        $this->entity->usuarios()->sync($request->usuarios);

        //        $created = $this->entity->create($request->all());

//        $created = $this->entity->create($request->all());


        /*$request->input('nombre')*/

        # Redirigimos a index
        return redirect()->route("$this->entity_name.index",['company' => $this->company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', creado con exito.');
    }

    /**
     * Display the specified resource
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($company,$id)
    {
        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'data' => $this->entity->findOrFail($id),
            'company' => $this->company,
            'users' => $this->users,
            'empresas' => $this->empresas,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($company,$id)
    {
        //dd($this->entity->find($id)->usuarios);
        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'data' => $this->entity->findOrFail($id),
            'company' => $this->company,
            'users' => $this->users,
            'empresas' => $this->empresas
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  integer	$id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$company ,$id)
    {
        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules);

//		$created = $this->entity->create($request->all());

        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        $entity->fk_id_usuario_actualiza = Auth::id();//Usuario que actualiza el registro
//        $entity->fecha_actualiza = DB::raw('now()');//Fecha y hora de la actualización
        $entity->save();


        /*$request->input('nombre')*/

        # Redirigimos a index
        return redirect()->route("$this->entity_name.index",['company' => $this->company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', actualizado con exito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer 	$id
     * @return \Illuminate\Http\Response
     */
    public function destroy($company,$id)
    {
        echo "Destroy";
        /*$entity = $this->entity->findOrFail($id);
        $entity->delete();*/
        $entity = $this->entity->findOrFail($id);
//        $entity->fk_id_usuario_elimina = Auth::id();//Usuario que elimina el registro
//        $entity->fecha_elimina = DB::raw('now()');//Fecha y hora de la eliminación
        $entity->eliminar='t';
        $entity->save();

        # Redirigimos a index
        return redirect()->route("$this->entity_name.index",['company' => $this->company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', borrado con exito.');
    }
}
