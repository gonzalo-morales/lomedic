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
use App\Http\Models\Administracion\Sucursales;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use App\Http\Models\Logs;
use Auth;
use DB;
use Illuminate\Support\Facades\Cache;

class UsuariosController extends ControllerBase
{
    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct()
    {
        $this->entity = new Usuarios;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company, $attributes = [])
    {
        // $sucursales = Sucursales::all();
        $sucursales_js = Crypt::encryptString('"conditions": [{"where": ["activo", "1"]}],"whereHas": [{"empresas":{"where":["fk_id_empresa", "$fk_id_empresa"]}}], "select": ["id_sucursal","sucursal"]');
        $empleados = Empleados::selectRaw("Concat(nombre,' ',apellido_paterno,' ',apellido_materno) as empleado, id_empleado")->where('activo',1)->pluck('empleado','id_empleado')->prepend('Seleccione el empleado',0);
        $companies = Empresas::all();
        $profiles = Perfiles::all();
        $profiles_permissions = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
            ->get();


        $attributes['dataview'] =[
            'companies'=>$companies,
            'profiles'=>$profiles,
            'profiles_permissions' => $profiles_permissions,
            'empleados'=>$empleados,
            'sucursales_js'=>$sucursales_js,
            // 'sucursales'=>$sucursales,
        ];

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
        $password = Hash::make($request->passowrd);
        $request->request->set('password',$password);
        $entity = $this->entity->create($request->all());
        if ($entity) {
            $id = $entity->id_usuario;

            if(isset($request->correo_empresa)) {
                $mails = [];
                foreach ($request->correo_empresa as $email_company) {
                    if($email_company) {
                        $mails[]= 
                        [
                            'correo' =>  $email_company['correo'],
                            'fk_id_empresa' =>  intval($email_company['id_empresa']),
                            'fk_id_usuario' =>  $id
                        ];
                    }
                }
                $entity->mails()->insert($mails);
            }

            if(isset($request->perfil)) {
                $sync = [];
                foreach ($request->perfil as $perfil) {
                    if($perfil) {
                        $sync[]= $perfil;
                    }
                }
                $entity->perfiles()->sync($sync);
            }

            foreach ( $request->input('accion_modulo') as $accion_modulo )
            {
                DB::table('adm_det_permisos_usuarios')->insert([
                    'fk_id_usuario' => $entity->id_usuario,
                    'fk_id_modulo_accion' => $accion_modulo,
                ]);
            }

            # Guardamos el detalle de sucursales en la que estara disponible
            if(isset($request->fk_id_sucursal)) {
                $sync = [];
                foreach ($request->fk_id_sucursal as $id_sucursal) {
                    if($id_sucursal) {
                        $sync[] = $id_sucursal;
                    }
                }
                $entity->usuario_sucursales()->sync($sync);
            }
            # Guardamos el detalle de empresa en la que estara disponible
            if(isset($request->fk_id_empresa)) {
                $sync = [];
                $sync[] = $request->fk_id_empresa;
                $entity->usuario_empresa()->sync($sync);
            }
            DB::commit();
            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();
            #$this->log('store', $id);
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
        $companies = Empresas::all();
        $correos = Correos::join('gen_cat_empresas','adm_det_correos.fk_id_empresa','=','gen_cat_empresas.id_empresa')
            ->where('fk_id_usuario','=',$id)
            ->get();
        $sucursales = Sucursales::whereHas('usuario_sucursales',function($q)use ($id){
            $q->where('fk_id_usuario','=',$id);
        })->get();
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
        $empleados = Empleados::whereHas('usuario',function($q)use ($data){
            $q->where('fk_id_empleado',$data->fk_id_empleado);
        })->selectRaw("Concat(nombre,' ',apellido_paterno,' ',apellido_materno) as empleado, id_empleado")->where('activo',1)->pluck('empleado','id_empleado')->prepend('Seleccione el empleado',0);
        $attributes['dataview'] =['companies'=>$companies];
        $dataview = isset($attributes['dataview']) ? $attributes['dataview'] : [];

        return view(currentRouteName('smart'), $dataview+[
            'data'=>$data,
            'correos'=>$correos,
            'profiles'=>$perfiles,
            'acciones' => $acciones,
            'sucursales'=>$sucursales,
            'empleados'=>$empleados,
        ]);
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
        $sucursales_js = Crypt::encryptString('"conditions": [{"where": ["activo", "1"]}],"whereHas": [{"empresas":{"where":["fk_id_empresa", "$fk_id_empresa"]}}], "select": ["id_sucursal","sucursal"]');
        $empleados = Empleados::selectRaw("Concat(nombre,' ',apellido_paterno,' ',apellido_materno) as empleado, id_empleado")->where('activo',1)->pluck('empleado','id_empleado')->prepend('Seleccione el empleado',0);
        $companies = Empresas::all();
        $perfiles = Perfiles::all();
        $profiles_permissions = Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
            ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
            ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
            ->get();
        $sucursalesAnteriores = Sucursales::whereHas('usuario_sucursales',function($q)use ($id){
            $q->where('fk_id_usuario','=',$id);
        })->get();
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
                                                                    'sucursales_anteriores'=>$sucursalesAnteriores,
                                                                    'correos'=>$correos_user,
                                                                    'profiles'=>$perfiles,
                                                                    'sucursales_js'=>$sucursales_js,
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
            $id = $entity->id_usuario;
            $correos_usuario = Correos::where('fk_id_usuario','=',$id)->get()->toArray();

            if( isset($request->correo_empresa ) )
            {
                foreach ($request->correo_empresa as $correo)
                {
                    if(array_search($correo['correo'], array_column($correos_usuario , 'correo')) === false)
                    {
                        Correos::create(['correo' => $correo['correo'],'fk_id_empresa'=>$correo['id_empresa'] ,'fk_id_usuario' => $id]);
                    }
                    else
                    {
                        Correos::where(['correo' => $correo['correo']])->update(['activo' => false]);
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

            # Guardamos el detalle de perfiles
            if(isset($request->perfil)) {
                $sync = [];
                foreach ($request->perfil as $perfil) {
                    if($perfil) {
                        $sync[]= $perfil;
                    }
                }
                $entity->perfiles()->sync($sync);
            }

            //         $mails[]= 
            //         [
            //             'correo' =>  $email_company['correo'],
            //             'fk_id_empresa' =>  intval($email_company['id_empresa']),
            //             'fk_id_usuario' =>  $id
            //         ];

            # Guardamos el detalle de sucursales en la que estara disponible
            if(isset($request->fk_id_sucursal)) {
                $sync = [];
                foreach ($request->fk_id_sucursal as $id_sucursal) {
                    if($id_sucursal) {
                        $sync[] = $id_sucursal;
                    }
                }
                $entity->usuario_sucursales()->sync($sync);
            }
            # Guardamos el detalle de empresa en la que estara disponible
            if(isset($request->fk_id_empresa_default)) {
                $sync = [];
                $sync[] = $request->fk_id_empresa_default;
                $entity->usuario_empresa()->sync($sync);
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
