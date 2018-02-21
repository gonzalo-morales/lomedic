<?php
namespace App\Http\Controllers\Finanzas;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Finanzas\CuentasContables;
use App\Http\Models\Administracion\AgrupadoresCuentas;

class CuentasContablesController extends ControllerBase
{
	/**
	 * Create a new controller instance.
	 * @return void
	 */
	public function __construct(CuentasContables $entity)
	{
		$this->entity = $entity;
	}
	
	public function getDataView($entity = null)
	{
	    return [
	        'tipos_cuentas' => collect([
    	            'A'=>'Activo Deudora', 'B'=>'Activo Acreedora', 'C'=>'Pasivo Deudora', 'D'=>'Pasivo Acreedora', 'E'=>'Capital Deudora', 'F'=>'Capital Acreedora',
    	            'G'=>'Resultados Deudora', 'H'=>'Resultados Acreedora', 'I'=>'Estadisticas Deudora', 'J'=>'Estadisticas Acreedora', 'K'=>'Orden Deudora', 'L'=>'Orden Acreedora'
    	        ]),
	        'cuentas_mayor' => collect([1=>'Si', 2=>'No', 3=>'De Titulo', 4=>'De Subtitulo']),
	        'cuentas_padre' => $this->entity->selectRaw("id_cuenta_contable, CONCAT(cuenta,' - ',nombre) as cuenta_contable")->where('activo',1)->pluck('cuenta_contable','id_cuenta_contable'),
	        'agrupadores_cuentas' => AgrupadoresCuentas::selectRaw("id_agrupador_cuenta, CONCAT(codigo_agrupador,' - ',nombre_cuenta) as agrupador_cuenta")->where('activo',1)->pluck('agrupador_cuenta','id_agrupador_cuenta'),
        ];
	}
}