<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\VehiculosMarcas;
use App\Http\Models\Administracion\VehiculosModelos;

class VehiculosModelosController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(VehiculosModelos $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        return [
            'brands' => VehiculosMarcas::select(['marca', 'id_marca'])->where('activo',1)->pluck('marca', 'id_marca'),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company, $attributes = [])
    {
        return parent::create($company, [
            'dataview' => []
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function show($company, $id, $attributes = [])
    {
        return parent::show($company, $id, [
            'dataview' => []
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  integer $id
     * @return \Illuminate\Http\Response
     */
    public function edit($company, $id, $attributes = [])
    {
        return parent::edit($company, $id, [
            'dataview' => []
        ]);
    }
}