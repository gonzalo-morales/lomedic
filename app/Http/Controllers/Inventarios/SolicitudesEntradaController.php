<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Inventarios\SolicitudesEntrada;
use Illuminate\Http\Request;

class SolicitudesEntradaController extends ControllerBase
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(SolicitudesEntrada $entity)
    {
        $this->entity = $entity;
    }

    public function getDataView($entity = null)
    {
        return [
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $company)
    {
        // $request->request->add([
        //     'fecha_solicitud' => Carbon::now()
        // ]);
        return parent::store($request, $company);
    }

}