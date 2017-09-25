<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Bancos;
use Illuminate\Http\Request;

class BancosController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct(Bancos $entity)
	{
		$this->entity = $entity;
	}

	public function store(Request $request, $company)
    {
        if($request->nacional == 'on')
            {$request->request->set('nacional',1);}
//        dd($request->request);
        return parent::store($request, $company);
    }
}