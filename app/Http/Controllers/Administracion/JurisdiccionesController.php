<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Estados;
use App\Http\Models\Administracion\Jurisdicciones;

class JurisdiccionesController extends ControllerBase
{
    public function __construct(Jurisdicciones $entity)
    {
        $this->entity = $entity;
        $this->states = Estados::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($company, $attributes = [])
    {
        return parent::create($company, [
            'dataview' => [
                'states' => $this->states,
            ]
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
            'dataview' => [
                'states' => $this->states,
            ]
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
            'dataview' => [
                'states' => $this->states,
            ]
        ]);
    }
}
