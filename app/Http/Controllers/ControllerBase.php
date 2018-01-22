<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade as PDF;
use Event;
use App\Events\LogModulos;

class ControllerBase extends Controller
{    
    public function getDataView($entity = null)
    {
        return [];
    }
    
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index($company, $attributes = [])
    {
        # ¿Usuario tiene permiso para ver?
        //$this->authorize('view', $this->entity);

        # Log
        event(new LogModulos($this->entity, $company, 'index' , null));
        
        $query = $this->entity->with($this->entity->getEagerLoaders())->orderby($this->entity->getKeyName(),'DESC');

        if(in_array('eliminar',$this->entity->getlistColumns())) {
            $query->where('eliminar',0);
        }

        if(isset($attributes['where'])) {
            foreach ($attributes['where'] as $key=>$condition) {
                $query->where(DB::raw($condition));
            }
        }

        if (!request()->ajax()) {
            $appendable = $this->entity->getAppendableFields();
            $all = $query->limit(20)->get();
            
            $page = request()->page ?: 1;
            $perPage = 4000;
            
            $items = $all->forPage($page, $perPage)->each(function($item) use ($appendable) {
                $item->setAppends($appendable);
                $item->setAttribute('data-atributes', $this->entity->getDataAttributes($item));
            });
            
            return view(currentRouteName('smart'), ($attributes['dataview'] ?? []) + [
                'fields' => $this->entity->getFields(),
                'data' => $items,
            ]);
        }
        else { # is Ajax
            $appendable = $this->entity->getAppendableFields();
            
            # Retorna resultados, los cache antes si no existen
            $cache = Cache::tags(getCacheTag())->rememberForever(getCacheKey(), function() use ($query, $appendable) {

                $all = $query->get();

                $page = request()->page ?: 1;
                $perPage = 4000;

                $items = $all->forPage($page, $perPage)->each(function($item) use ($appendable) {
                    $item->setAppends($appendable);
                    $item->setAttribute('data-atributes', currentEntity()->getDataAttributes($item));
                });

                # Eliminamos primeros 20 registros en pagina #1
                if( $page == 1) $items = $items->slice(20);

                return (new LengthAwarePaginator($items, $all->count(), $perPage, $page));
            });
                return $cache;
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return \Illuminate\Http\Response
     */
    public function create($company, $attributes =[])
    {
        # ¿Usuario tiene permiso para crear?
        //$this->authorize('create', $this->entity);
        
        # Log
        event(new LogModulos($this->entity, $company, __FUNCTION__ , 'Antes de crear el registro'));
        
        $data = !isset($attributes['id']) ? $this->entity->getColumnsDefaultsValues() : $this->entity->find($attributes['id']);
        $validator = \JsValidator::make(($this->entity->rules ?? []) + $this->entity->getRulesDefaults(), [], $this->entity->niceNames, '#form-model');

        return view(currentRouteName('smart'), ($attributes['dataview'] ?? []) + [
            'data' => $data,
            'validator' => $validator
        ] + $this->getDataView());
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $company, $compact = false)
    {
        # ¿Usuario tiene permiso para crear?
        //$this->authorize('create', $this->entity);

        $request->request->set('activo',!empty($request->request->get('activo')));

        # Validamos request, si falla regresamos pagina
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);

        DB::beginTransaction();
        $entity = $this->entity->create($request->all());
        if ($entity) {

            # Si tienes relaciones
            if ($request->relations) {
                foreach ($request->relations as $relationType => $collections) {
                    # Relacion "HAS"
                    if ($relationType == 'has') {
                            foreach ($collections as $relationName => $relations){
                                if(isset($relations['-1'])) {
                                    unset($relations['-1']);
                                }
                                $entity->{$relationName}()->createMany($relations);
                            }
                    }
                }
            }

            DB::commit();

            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();

            # Log
            event(new LogModulos($entity, $company, 'crear' , 'Registro creado'));
            
            $redirect = $this->redirect('store');
        } else {
            DB::rollBack();
            # Log
            event(new LogModulos($this->entity, $company, 'crear' , 'Error al crear el registro'));
            $redirect = $this->redirect('error_store');
        }
        
        return $compact ? compact('entity','redirect') : $redirect;
    }

    /**
     * Display the specified resource
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($company, $id, $attributes =[])
    {
        # ¿Usuario tiene permiso para ver?
        //$this->authorize('view', $this->entity);
        
        try {
            $data = $this->entity->findOrFail($id);
        } catch (\Exception $e) {
            return \App::abort(404,implode(' ',$e->errorInfo));
        }
        # Log
        event(new LogModulos($data, $company, 'ver', null));

        header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
        header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past

        if (!request()->ajax()) {
            return view(currentRouteName('smart'), ($attributes['dataview'] ?? []) + ['data'=>$data] + $this->getDataView($data));

            # Ajax
        } else {

            if (request()->with) {
                $data->load(request()->with);
            }
            return response()->json(['success' => true, 'data' => $data->toArray()])->header("Vary", "Accept");
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($company, $id, $attributes =[])
    {
        # ¿Usuario tiene permiso para actualizar?
        //$this->authorize('update', $this->entity);

        $validator = \JsValidator::make(($this->entity->rules ?? []) + $this->entity->getRulesDefaults(), [], $this->entity->niceNames, '#form-model');

        try {
            $data = $this->entity->findOrFail($id);
        } catch (\Exception $e) {
            return \App::abort(404,$e->getMessage());
        }

        return view(currentRouteName('smart'), ($attributes['dataview'] ?? []) + [
            'data' => $data,
            'validator' => $validator
        ] + $this->getDataView($data));
    }

    /**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $company, $id, $compact = false)
    {
        # ¿Usuario tiene permiso para actualizar?
        //$this->authorize('update', $this->entity);

        $request->request->set('activo',!empty($request->request->get('activo')));

        # Validamos request, si falla regresamos atras
        $this->validate($request, $this->entity->rules, [], $this->entity->niceNames);

        DB::beginTransaction();
        $entity = $this->entity->findOrFail($id);
        $entity->fill($request->all());
        if ($entity->save()) {
            # Si tienes relaciones
            if ($request->relations) {
                foreach ($request->relations as $relationType => $collections) {
                    # Relacion "HAS"
                    if ($relationType == 'has') {
                        foreach ($collections as $relationName => $relations) {
                            # Recorremos cada coleccion
                            $primaryKey = $entity->{$relationName}()->getRelated()->getKeyName();

                            $ids = collect($relations)->pluck($primaryKey);
                            if (!empty($ids)) {
                                $entity->{$relationName}()->whereNotIn($primaryKey, $ids)->update(['eliminar' => 1]);
                            } else {
                                $entity->{$relationName}()->update(['eliminar' => 1]);
                            }
                            
                            if(isset($relations['-1'])) {
                                unset($relations['-1']);
                            }

                            foreach ($relations as $relation) {
                                $primary_id = isset($relation[$primaryKey]) && $relation[$primaryKey] != 1 ? $relation[$primaryKey] : null;
                                $entity->{$relationName}()->updateOrCreate([$primaryKey => $primary_id], $relation);
                            }
                        }
                    }
                }
            }
            DB::commit();

            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();

            # Log
            event(new LogModulos($entity, $company, 'editar', 'Registro actualizado'));
            
            $redirect = $this->redirect('update');
        } else {
            DB::rollBack();
            # Log
            event(new LogModulos($entity, $company, 'editar', 'Error al actualizar el registro'));
            
            $redirect = $this->redirect('error_update');
        }
        return $compact ? compact('entity','redirect') : $redirect;
    }

    /**
     * Remove the specified resource from storage.
     * @param  integer  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $company, $idOrIds, $attributes = ['eliminar' => 't'])
    {
        # ¿Usuario tiene permiso para eliminar?
        //$this->authorize('delete', $this->entity);

        $idOrIds = !is_array($idOrIds) ? [$idOrIds] : $idOrIds;

        DB::beginTransaction();
        $isSuccess = $this->entity->whereIn($this->entity->getKeyName(), $idOrIds)->update($attributes);
        
        if ($isSuccess) {
            DB::commit();
            # Shorthand
            foreach ($idOrIds as $id) {
                $entity = $this->entity->findOrFail($id);
                # Log
                event(new LogModulos($entity, $company, 'eliminar', 'Eliminacion de registro'));
            }

            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();

            if ($request->ajax()) {
                # Respuesta Json
                return ['success' => true];
            } else {
                return $this->redirect('destroy');
            }
        }
        else {
            DB::rollBack();
            # Shorthand
            foreach ($idOrIds as $id) {
                $entity = $this->entity->findOrFail($id);
                # Log
                event(new LogModulos($entity, $company, 'eliminar', 'Erro al eliminar registro'));
            }

            if ($request->ajax()) {
                # Respuesta Json
                return ['success' => false];
            } else {
                return $this->redirect('error_destroy');
            }
        }
    }

    /**
     * Remove multiple resources from storage.
     * @param  Request $request
     * @param  string  $company
     * @return \Illuminate\Http\Response
     */
    public function destroyMultiple(Request $request, $company)
    {
        # ¿Usuario tiene permiso para eliminar?
        //$this->authorize('delete', $this->entity);

        # Shorthand
        if ($request->ids)
            return $this->destroy($request, $company, $request->ids);

        return ['success' => false];
    }

    /**
     * Obtenemos reporte
     * @param  string $company
     * @return file
     */
    public function export(Request $request, $company)
    {
        # ¿Usuario tiene permiso para exportar?
        //$this->authorize('export', $this->entity);

        $colums = $this->entity->getlistColumns();

        $type = strtolower($request->type);
        $style = isset($request->style) ? $request->style : false;

        if (isset($request->ids)) {
            $ids = is_array($request->ids) ? $request->ids : explode(',',$request->ids);
            $query = $this->entity->with($this->entity->getEagerLoaders())->orderby($this->entity->getKeyName(),'DESC')->whereIn($this->entity->getKeyName(), $ids);
        }
        else {
            $query = $this->entity->with($this->entity->getEagerLoaders())->orderby($this->entity->getKeyName(),'DESC');
        }

        if(in_array('eliminar',$colums))
            $query->where('eliminar',0);

        # Log
        event(new LogModulos($this->entity, $company, 'exportar' , 'Exportacion de registros a: '.$type));

        $fields = $this->entity->getFields();
        $data = $query->get();
        
        if($type == 'pdf') {
            $pdf= PDF::loadView(currentRouteName('smart'), ['fields' => $fields, 'data' => $data]);
            $pdf->setPaper('letter','landscape');
            $pdf->output();
            $dom_pdf = $pdf->getDomPDF();
            $canvas = $dom_pdf->get_canvas();
            $canvas->page_text(38,580,"Pagina {PAGE_NUM} de {PAGE_COUNT}",null,8,array(0,0,0));
            return $pdf->stream(currentEntityBaseName().'.pdf')->header('Content-Type',"application/$type");
        }
        else {
            Excel::create(currentEntityBaseName(), function($excel) use($data,$type,$style,$fields) {
                $excel->sheet(currentEntityBaseName(), function($sheet) use($data,$type,$style,$fields) {
                        $sheet->loadView(currentRouteName('smart'), ['fields' => $fields, 'data' => $data]);
                });
            })->download($type);
        }
    }

    public function redirect($type)
    {
        switch ($type) {
            case 'store':
                $message = ['type'=> 'toast_success', 'text' => 'Registro creado correctamente.'];
                break;
            case 'error_store':
                $message = ['type'=> 'toast_error', 'text' => 'No fue posible crear registro.'];
                break;
            case 'update':
                $message = ['type'=> 'toast_success', 'text' => 'Registro actualizado correctamente.'];
                break;
            case 'error_update':
                $message = ['type'=> 'toast_error', 'text' => 'No fue posible actualizar registro.'];
                break;
            case 'destroy':
                $message = ['type'=> 'toast_success', 'text' => 'Registro (s) eliminado correctamente.'];
                break;
            case 'error_destroy':
                $message = ['type'=> 'toast_error', 'text' => 'No fue posible eliminar registro (s).'];
                break;
            default:
                break;
        }
        return redirect(companyRoute('index'))->with('message', $message);
    }
}