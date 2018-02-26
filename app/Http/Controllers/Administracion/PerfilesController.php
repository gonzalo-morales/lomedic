<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Models\Administracion\Perfiles;
use App\Http\Models\Administracion\Modulos;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Administracion\Empresas;
use App\Http\Controllers\ControllerBase;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Models\Logs;
use Auth;
use DB;
use Illuminate\Support\Facades\Cache;

class PerfilesController extends ControllerBase
{

    public function __construct(Perfiles $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        if($entity)
        {
            // dd($entity);
            return [
                'usuarios' => Usuarios::select('id_usuario','usuario')->whereHas('perfiles',function($q) use ($entity){
                    $q->where('fk_id_perfil',$entity->fk_id_perfil);
                })->where('activo',1)->get()->sortBy('usuario')->toArray(),
                'companies' => Empresas::all(),
                'profiles' => Perfiles::all(),
                'profiles_permissions' => Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
                    ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
                    ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
                    ->get(),
            ];
        }
        return [
            'usuarios' => Usuarios::select('id_usuario','usuario')->where('activo',1)->get()->sortBy('usuario')->toArray(),
            'companies' => Empresas::all(),
            'profiles' => Perfiles::all(),
            'profiles_permissions' => Perfiles::join('adm_det_permisos_perfiles','adm_cat_perfiles.id_perfil','=','adm_det_permisos_perfiles.fk_id_perfil')
                ->join('adm_det_modulo_accion','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_perfiles.fk_id_modulo_accion')
                ->select('adm_det_modulo_accion.*','adm_det_permisos_perfiles.fk_id_perfil')
                ->get(),
        ];
    }

    public function store(Request $request, $company, $compact = false)
    {
		$this->validate($request, $this->entity->rules);
		DB::beginTransaction();
        $entity = $this->entity->create($request->all());
        // dd($request);
		if ($entity)
		{
            // dd($request->accion_modulo);
			$id = $entity->id_perfil;
 
			# Guardamos el detalle de usuarios en la que estara disponible
            if(isset($request->usuarios))
            {
				$sync = [];
                foreach ($request->usuarios as $id_usuario=>$active)
                {
                    if($active)
                    {
                        $sync[] = $id_usuario;
					}
				}
                $entity->usuarios()->sync($sync);
            }
            // dd('$sync');
            if(isset($request->accion_modulo))
            {
                foreach ( $request->accion_modulo as $accion_modulo )
                {
                    DB::table('adm_det_permisos_perfiles')->insert([
                        'fk_id_perfil' => $id,
                        'fk_id_modulo_accion' => $accion_modulo,
                    ]);
                }
            }

            // if(isset($request->accion_modulo)) 
            // {
			// 	$sync = [];
            //     foreach ($request->accion_modulo as $accion_modulo) 
            //     {
            //         if($accion_modulo) 
            //         {
			// 			$sync[] = $id_usuario;
			// 		}
			// 	}
			// 		$entity->usuarios()->sync($sync);
            // }

			DB::commit();
 
			# Eliminamos cache
			Cache::tags(getCacheTag('index'))->flush();
			#$this->log('store', $id);
			return $this->redirect('store');
        } 
        else
        {
			DB::rollBack();
			#$this->log('error_store');
			return $this->redirect('error_store');
		}
    }

	public function update(Request $request, $company, $id, $compact = false)
	{
	    # ¿Usuario tiene permiso para actualizar?
	    #$this->authorize('update', $this->entity);

	    # Validamos request, si falla regresamos atras
	    $this->validate($request, $this->entity->rules);

	    #DB::beginTransaction();
	    $entity = $this->entity->findOrFail($id);
		$entity->fill($request->all());
		if ($entity->save())
		{
			$id = $entity->id_perfil;
 
			# Guardamos el detalle de usuarios en la que estara disponible
            if(isset($request->usuarios)) 
            {
				$sync = [];
                foreach ($request->usuarios as $id_usuario=>$active) 
                {
                    if($active) 
                    {
						$sync[] = $id_usuario;
					}
				}
					$entity->usuarios()->sync($sync);
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

            // if(isset($request->accion_modulo)) 
            // {
			// 	$sync = [];
            //     foreach ($request->accion_modulo as $accion_modulo) 
            //     {
            //         if($accion_modulo) 
            //         {
			// 			$sync[] = $id_usuario;
			// 		}
			// 	}
			// 		$entity->usuarios()->sync($sync);
            // }

			DB::commit();
 
			# Eliminamos cache
			Cache::tags(getCacheTag('index'))->flush();
			#$this->log('store', $id);
			return $this->redirect('store');
        } 
        else
        {
			DB::rollBack();
			#$this->log('error_store');
			return $this->redirect('error_store');
		}
	}
}