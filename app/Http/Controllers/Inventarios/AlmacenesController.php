<?php

namespace App\Http\Controllers\Inventarios;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TipoAlmacen;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\Ubicaciones;

class AlmacenesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
	    $this->entity = new Almacenes;
	}

	public function getDataView($entity = null)
	{
		return [
			'ubicaciones' => $entity ? $entity->ubicaciones()->orderby('id_ubicacion', 'DESC')->get() : [],
		    'sucursales' => Sucursales::select(['sucursal','id_sucursal'])->where('activo',1)->pluck('sucursal','id_sucursal'),
		    'tipos' => TipoAlmacen::select(['tipo','id_tipo'])->where('activo',1)->pluck('tipo','id_tipo'),
		];
	}

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($company, $attributes = [])
    {
    	// Al crear un almacen, redirige a index (es decir aqui), vamos a calcular el codigo de barras de las ubicaciones asociadas al almacen recien creado
		Ubicaciones::where('codigo_barras', null)->each(function($ubicacion){
			//
			$codigo = str_pad($ubicacion->id_ubicacion, 7, '0', STR_PAD_LEFT);
			$codigo_array = str_split($codigo);
			$codigo_verificacion = $codigo_array[0]*3;
			$codigo_verificacion += $codigo_array[1];
			$codigo_verificacion += $codigo_array[2]*3;
			$codigo_verificacion += $codigo_array[3];
			$codigo_verificacion += $codigo_array[4]*3;
			$codigo_verificacion += $codigo_array[5];
			$codigo_verificacion += $codigo_array[6]*3;
			$codigo_verificacion = (ceil($codigo_verificacion/10)*10)-$codigo_verificacion;
			//
			$ubicacion->codigo_barras = $codigo.$codigo_verificacion;
			$ubicacion->save();
		});

		return parent::index($company, $attributes);
    }
}