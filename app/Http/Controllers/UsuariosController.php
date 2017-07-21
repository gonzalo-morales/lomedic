<?php

namespace App\Http\Controllers;

use App\Http\Models\Bancos;
use App\Http\Models\Usuarios;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Auth;
use DB;

class UsuariosController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Usuarios $entity)
    {
        // $this->middleware('auth');
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
        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->all()->where('eliminar','=', false),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company)
    {
        return view(Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
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
        $this->entity->fill($request->all());
        $this->entity->password = Hash::make($request->get('password'));
        $this->entity->fk_id_usuario_crea = Auth::id();
        $this->entity->save();
        # Redirigimos a index
        return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', creado con exito.');

    }

    /**
     * Display the specified resource
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($company, $id)
    {
        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->findOrFail($id),
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
        return view (Route::currentRouteName(), [
            'entity' => $this->entity_name,
            'company' => $company,
            'data' => $this->entity->findOrFail($id),
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
        //$this->validate($request, $this->entity->rules);

        $entity = $this->entity->findOrFail($id);
        $entity->fk_id_usuario_actualiza = Auth::id();
        $entity->fill($request->all());

        $entity->save();

        # Redirigimos a index
        return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', actualizado con exito.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer 	$id
     * @return \Illuminate\Http\Response
     */
    public function destroy($company, $id)
    {
        /*
        $entity = $this->entity->findOrFail($id);
        $entity->delete();
        */

        $entity = $this->entity->findOrFail($id);
        $entity->fk_id_usuario_elimina = Auth::id();//Usuario que elimina el registro
        $entity->fecha_elimina = DB::raw('now()');//Fecha y hora de la eliminación
        $entity->eliminar='t';
        $entity->save();

        # Redirigimos a index
        return redirect()->route("$this->entity_name.index", ['company'=> $company])->with('success', trans_choice('messages.'.$this->entity_name, 0) .', borrado con exito.');
    }
}
