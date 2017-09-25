<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\FamiliasProductos;
use App\Http\Models\Administracion\TiposProductos;
use Illuminate\Http\Request;

class FamiliasproductosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(FamiliasProductos $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView()
	{
		return [
			'companies' => Empresas::active()->select(['nombre_comercial','id_empresa'])->pluck('nombre_comercial','id_empresa'),
		];
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create($company, $attributes = [])
	{
//		$this->loadResources();
        $attributes = $attributes+ $attributes+['dataview'=>[
            'product_types'=>TiposProductos::where('estatus',1)->pluck('descripcion','id_tipo')
        ]];
		return parent::create($company,$attributes);
	}

	public function store(Request $request, $company)
    {
        if($request->activo == 'on')
            {$request->request->set('estatus',1);}

        return parent::store($request, $company);
    }

    /**
	 * Display the specified resource
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function show($company, $id, $attributes = [])
	{
//		$this->loadResources();
        $attributes = $attributes+ $attributes+['dataview'=>[
                'product_types'=>TiposProductos::where('estatus',1)->pluck('descripcion','id_tipo'),
            ]];
		return parent::show($company, $id, $attributes);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  integer $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($company, $id, $attributes = [])
	{
//		$this->loadResources();
        $attributes = $attributes+ $attributes+['dataview'=>[
                'product_types'=>TiposProductos::where('estatus',1)->pluck('descripcion','id_tipo'),
            ]];
		return parent::edit($company, $id, $attributes);
	}
}
