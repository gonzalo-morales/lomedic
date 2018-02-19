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
use App\Http\Models\Administracion\PermisosUsuarios;
use App\Http\Models\Administracion\PerfilesUsuarios;
use App\Http\Models\RecursosHumanos\Empleados;

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
     * @return void
     */
    public function __construct(Usuarios $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company, $attributes = [])
    {
        $empleados = Empleados::selectRaw("Concat(nombre,' ',apellido_paterno,' ',apellido_materno) as empleado, id_empleado")->where('activo',1)->pluck('empleado','id_empleado');
        $companies = Empresas::all();
        $profiles = Perfiles::all();
        $profiles_permissions = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
            ->get();


        $attributes['dataview'] =['companies'=>$companies,'profiles'=>$profiles,'profiles_permissions' => $profiles_permissions,'empleados'=>$empleados];

        return parent::create($company,$attributes);

    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */

    public function store(Request $request, $company, $compact = false)
    {

        $isSuccess = $this->entity->create($request->all());
        if ($isSuccess) {

            foreach ( $request->input('correo_empresa') as $email_company )
            {
                DB::table('adm_det_correos')->insert([
                    'correo'=> $email_company['correo'],
                    'fk_id_empresa'=> $email_company['id_empresa'],
                    'fk_id_usuario' => $isSuccess->id_usuario,
                ]);
            }

            foreach ( $request->input('perfil') as $perfil )
            {
                $id_perfil = explode( '_', $perfil );
                DB::table('adm_det_perfiles_usuarios')->insert([
                    'fk_id_perfil' => $perfil,
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
            #$this->log('error_store');
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

        $this->authorize('view', $this->entity);
        $companies = Empresas::all();
        $correos = Correos::join('gen_cat_empresas','adm_det_correos.fk_id_empresa','=','gen_cat_empresas.id_empresa')
            ->where('fk_id_usuario','=',$id)
            ->get();

        $perfiles = Perfiles::join('adm_det_perfiles_usuarios','adm_cat_perfiles.id_perfil','=','adm_det_perfiles_usuarios.fk_id_perfil')
            ->where('fk_id_usuario','=',$id)
            ->get();

//        $profiles_permissions = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
//            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
//            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
//            ->get();

        $acciones = ModulosAcciones::join('adm_det_permisos_usuarios','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_usuarios.fk_id_modulo_accion')
            ->where('fk_id_usuario','=',$id)
            ->get();
        # Log
        #$this->log('show', $id);
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
        $empleados = Empleados::selectRaw("Concat(nombre,' ',apellido_paterno,' ',apellido_materno) as empleado, id_empleado")->where('activo',1)->pluck('empleado','id_empleado');
        $companies = Empresas::all();
        $perfiles = Perfiles::all();
        $profiles_permissions = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
            ->get();
        $correos_user = Correos::join('gen_cat_empresas','adm_det_correos.fk_id_empresa','=','gen_cat_empresas.id_empresa')
            ->where('fk_id_usuario','=',$id)
            ->where('adm_det_correos.activo','=','true')
            ->get();
        $perfiles_usuario = Perfiles::join('adm_det_perfiles_usuarios','adm_cat_perfiles.id_perfil','=','adm_det_perfiles_usuarios.fk_id_perfil')
            ->where('fk_id_usuario','=',$id)
            ->get();
        $acciones_usuario = ModulosAcciones::join('adm_det_permisos_usuarios','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_usuarios.fk_id_modulo_accion')
            ->join('adm_cat_acciones','adm_det_modulo_accion.fk_id_accion','=','adm_cat_acciones.id_accion')
            ->select('id_modulo_accion','nombre')
            ->where('fk_id_usuario','=',$id)
            ->get();

        # Log
        #$this->log('show', $id);

        # ¿Usuario tiene permiso para actualizar?
        #$this->authorize('update', $this->entity);
        $data = $this->entity->findOrFail($id);
        $dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];
        return view(currentRouteName('smart'), $dataview+['companies'=>$companies,
                                                                    'data'=>$data,
                                                                    'empleados'=>$empleados,
                                                                    'correos'=>$correos_user,
                                                                    'profiles'=>$perfiles,
                                                                    'perfiles_usuario'=>$perfiles_usuario,
                                                                    'acciones_usuario' => $acciones_usuario,
                                                                    'profiles_permissions'=>$profiles_permissions]);
    }


    public function update(Request $request, $company, $id, $compact = false)
    {
//        # ¿Usuario tiene permiso para actualizar?
//        $this->authorize('update', $this->entity);
//
//        $request->request->set('activo',!empty($request->request->get('activo')));
//
//        # Validamos request, si falla regresamos atras
//        $this->validate($request, $this->entity->rules);

        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        if ($entity->save()) {

            $correos_usuario = Correos::where('fk_id_usuario','=',$id)->get()->toArray();

            if( isset($request->correo_empresa ) )
            {
                foreach ($request->correo_empresa as $correo)
                {
                    if(array_search($correo['correo'], array_column($correos_usuario , 'correo')) === false)
                    {
                        Correos::create(['correo' => $correo['correo'],'fk_id_empresa'=>$correo['id_empresa'] ,'fk_id_usuario' => $id]);
                    }
                }
            }

            foreach ($correos_usuario as $correo)
            {
                if( isset($request->correo_empresa ) )
                {
                    if (array_search($correo['correo'], array_column($request->correo_empresa, 'correo')) === false)
                    {
                        Correos::where(['correo' => $correo['correo']])->update(['activo' => false]);
                    }
                }
                else
                {
                    Correos::where(['correo' => $correo['correo']])->update(['activo' => false]);
                }
            }


            $acciones_usuario = PermisosUsuarios::where('fk_id_usuario','=',$id)->get()->toArray();
            if(isset($request->accion_modulo))
            {
                foreach ($request->accion_modulo as $accion)
                {
                    if (array_search($accion, array_column($acciones_usuario, 'fk_id_modulo_accion'))=== false)
                    {
                        if(PermisosUsuarios::where('fk_id_usuario',$id)->where('fk_id_modulo_accion',$accion)->first()== '')
                        {
                            PermisosUsuarios::create(['fk_id_usuario' => $id,'fk_id_modulo_accion' => $accion]);
                        }
                    }
                }
            }

            if(isset($request->accion_modulo)){

                foreach ($acciones_usuario as $accion)
                {

                    if (array_search($accion['fk_id_modulo_accion'], $request->accion_modulo) === false)
                    {
                        PermisosUsuarios::where('fk_id_usuario','=',$id)
                            ->where('fk_id_modulo_accion','=',$accion['fk_id_modulo_accion'])
                            ->delete();
                    }
                }

            }
            else
            {
                foreach ($acciones_usuario as $accion)
                {
                    PermisosUsuarios::where('fk_id_usuario','=',$id)
                        ->where('fk_id_modulo_accion','=',$accion['fk_id_modulo_accion'])
                        ->delete();
                }
            }

            $perfiles_usuario = PerfilesUsuarios::where('fk_id_usuario','=',$id)->get()->toArray();
            if(isset($request->perfil))
            {

                foreach ($request->perfil as $perfil)
                {
                    if (array_search($perfil, array_column($perfiles_usuario, 'fk_id_perfil')) === false)
                    {
                        if(PerfilesUsuarios::where('fk_id_usuario',$id)->where('fk_id_perfil',$perfil)->first()== '')
                        {
                            PerfilesUsuarios::create(['fk_id_usuario' => $id,'fk_id_perfil' => $perfil]);
                        }
                    }
                }
            }

            if(isset($request->perfil)){

                foreach ($perfiles_usuario as $perfil)
                {
//                    dump($perfil);
//                    dump($request->perfil);
//                    dump(array_search($perfil['fk_id_perfil'], $request->perfil));
                    if (array_search($perfil['fk_id_perfil'], $request->perfil) === false)
                    {
                        PerfilesUsuarios::where('fk_id_usuario','=',$id)
                            ->where('fk_id_perfil','=',$perfil['fk_id_perfil'])
                            ->delete();
                    }
                }

            }
            else
            {
                foreach ($perfiles_usuario as $perfil)
                {
                    PerfilesUsuarios::where('fk_id_usuario','=',$id)
                        ->where('fk_id_perfil','=',$perfil['fk_id_perfil'])
                        ->delete();
                }
            }


            # Eliminamos cache
//            Cache::tags(getCacheTag('index'))->flush();

            #$this->log('update', $id);
            return $this->redirect('update');
        } else {
            #$this->log('error_update', $id);
            return $this->redirect('error_update');
        }
    }


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
