<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Correos;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\Modulos;
use App\Http\Models\Administracion\Perfiles;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Administracion\Acciones;
use App\Http\Models\Administracion\ModulosAcciones;
use App\Http\Controllers\ControllerBase;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use App\Http\Models\Logs;
use Auth;
use DB;

class UsuariosController extends ControllerBase
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Usuarios $entity)
    {
        $this->entity = $entity;
        $this->entity_name = strtolower(class_basename($entity));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company, $attributes = Array())
    {

        $companies = Empresas::all();
        $profiles = Perfiles::all();
        $profiles_permissions = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
            ->get();


        $attributes['dataview'] =['companies'=>$companies,'profiles'=>$profiles,'profiles_permissions' => $profiles_permissions];

        return parent::create($company,$attributes);

    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */

    public function store(Request $request, $company)
    {
        # ¿Usuario tiene permiso para crear?
//        $this->authorize('create', $this->entity);

        # Validamos request, si falla regresamos pagina
//        $this->validate($request, $this->entity->rules);
//        dd($request);

//        dd($request->all());

        $isSuccess = $this->entity->create($request->all());
        if ($isSuccess) {
//            $this->log('store', $isSuccess->id_usuario);

            foreach ( $request->input('correo_empresa') as $email_company )
            {
                DB::table('adm_det_correos')->insert([
                    'correo'=> $email_company['correo'],
                    'fk_id_empresa'=> $email_company['id_empresa'],
                    'fk_id_usuario' => $isSuccess->id_usuario,
                ]);
            }

            foreach ($request->input('perfil') as $perfil )
            {
                $id_perfil = explode( '_', $perfil );
                DB::table('adm_det_perfiles_usuarios')->insert([
                    'fk_id_perfil' => $id_perfil[1],
                    'fk_id_usuario' => $isSuccess->id_usuario,
                ]);
            }
//            dump($request->input('accion_modulo'));
            foreach ( $request->input('accion_modulo') as $accion_modulo )
            {
                DB::table('adm_det_permisos_usuarios')->insert([
                    'fk_id_usuario' => $isSuccess->id_usuario,
                    'fk_id_modulo_accion' => $accion_modulo,
                ]);
            }



            return $this->redirect('store');
        } else {
            $this->log('error_store');
            return $this->redirect('error_store');
        }
    }

//
//    /**
//     * Display the specified resource
//     *
//     * @param  integer $id
//     * @return \Illuminate\Http\Response
//     */
    public function show($company, $id, $attributes =[])
    {
        # ¿Usuario tiene permiso para ver?

//        dd($this->entity);

        $this->authorize('view', $this->entity);
        $companies = Empresas::all();
        $correos = Correos::join('gen_cat_empresas','adm_det_correos.fk_id_empresa','=','gen_cat_empresas.id_empresa')
            ->where('fk_id_usuario','=',$id)
            ->get();
//        dd($correos);
        $perfiles = Perfiles::join('adm_det_perfiles_usuarios','adm_cat_perfiles.id_perfil','=','adm_det_perfiles_usuarios.fk_id_perfil')
            ->where('fk_id_usuario','=',$id)
            ->get();
//        dd($perfiles);
//        $profiles_permissions = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
//            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
//            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
//            ->get();

        $acciones = ModulosAcciones::join('adm_det_permisos_usuarios','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_usuarios.fk_id_modulo_accion')
            ->where('fk_id_usuario','=',$id)
            ->get();
        # Log
        $this->log('show', $id);
        $data = $this->entity->findOrFail($id);
        $attributes['dataview'] =['companies'=>$companies];
        $dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];

        return view(currentRouteName('smart'), $dataview+['data'=>$data,'correos'=>$correos,'profiles'=>$perfiles,'acciones' => $acciones]);
    }
//
//    /**
//     * Show the form for editing the specified resource.
//     *
//     * @param  integer $id
//     * @return \Illuminate\Http\Response
//     */
//    public function edit($company, $id)
//    {
//        return view (Route::currentRouteName(), [
//            'entity' => $this->entity_name,
//            'company' => $company,
//            'data' => $this->entity->findOrFail($id),
//        ]);
//    }
    public function edit($company, $id, $attributes =[])
    {
        $companies = Empresas::all();
        $perfiles = Perfiles::all();
        $acciones = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
            ->get();
        $profiles_permissions = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
            ->get();

//        $companies_user = Empresas::all();
        $correos_user = Correos::join('gen_cat_empresas','adm_det_correos.fk_id_empresa','=','gen_cat_empresas.id_empresa')
            ->where('fk_id_usuario','=',$id)
            ->get();
        $perfiles_usuario = Perfiles::join('adm_det_perfiles_usuarios','adm_cat_perfiles.id_perfil','=','adm_det_perfiles_usuarios.fk_id_perfil')
            ->where('fk_id_usuario','=',$id)
            ->get();


        $acciones_usuario = ModulosAcciones::join('adm_det_permisos_usuarios','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_usuarios.fk_id_modulo_accion')
            ->where('fk_id_usuario','=',$id)
            ->get();

        # Log
        $this->log('show', $id);

        # ¿Usuario tiene permiso para actualizar?
        #$this->authorize('update', $this->entity);
        $data = $this->entity->findOrFail($id);
        $dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];
        return view(currentRouteName('smart'), $dataview+['companies'=>$companies,
                                                                    'data'=>$data,
                                                                    'correos'=>$correos_user,
                                                                    'profiles'=>$perfiles,
                                                                    'perfiles_usuario'=>$perfiles_usuario,
                                                                    'acciones' => $acciones,
                                                                    'acciones_usuario' => $acciones_usuario,
                                                                    'profiles_permissions'=>$profiles_permissions]);
//        return view(currentRouteName('smart'), $dataview+['data'=>$data,'companies'=>$companies,'profiles'=>$profiles,'profiles_permissions'=>$profiles_permissions]);
    }

//
//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  integer	$id
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, $company, $id)
//    {
//        # Validamos request, si falla regresamos pagina
//        //$this->validate($request, $this->entity->rules);
//
//        $entity = $this->entity->findOrFail($id);
//        $entity->fill($request->all());
//
//        if($entity->save())
//        {Logs::createLog($this->entity->getTable(),$company,$id,'editar','Registro actualizado');}
//        else
//        {Logs::createLog($this->entity->getTable(),$company,$id,'editar','Error al editar');}
//        # Redirigimos a index
//        return redirect(companyRoute('index'));
//    }
//
//    /**
//     * Remove the specified resource from storage.
//     *
//     * @param  integer 	$id
//     * @return \Illuminate\Http\Response
//     */
//    public function destroy($company, $id)
//    {
//        /*
//        $entity = $this->entity->findOrFail($id);
//        $entity->delete();
//        */
//
//        $entity = $this->entity->findOrFail($id);
//        //$entity->fk_id_usuario_elimina = Auth::id();//Usuario que elimina el registro
//        //$entity->fecha_elimina = DB::raw('now()');//Fecha y hora de la eliminación
//        $entity->eliminar='t';
//        if($entity->save())
//        {Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Registro eliminado');}
//        else
//        {Logs::createLog($this->entity->getTable(),$company,$id,'eliminar','Error al editar');}
//
//        # Redirigimos a index
//        return redirect(companyRoute('index'));
//    }
}
