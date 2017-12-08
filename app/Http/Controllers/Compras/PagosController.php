<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Compras\FacturasProveedores;
use Illuminate\Support\Facades\Crypt;
use DB;

class PagosController extends ControllerBase
{
	public function __construct(FacturasProveedores $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {
        return [
            'bancos'=>Bancos::where('activo','f')->pluck('banco','id_banco'),
            'formas_pago'=>FormasPago::select('id_forma_pago',"concat('(',forma_pago,') ',descripcion) as text")->where('activo','t')->pluck('text','id_forma_pago'),
            'monedas'=>Monedas::select("concat('(',moneda,') ',descripcion) as text",'id_moneda')->where('activo','t')->pluck('text','id_moneda'),
//            'js_comprador' => Crypt::encryptString('"select": ["nombre","apellido_paterno","apellido_materno"], "conditions": [{"where": ["activo","1"]}], "whereHas": [{"ejecutivocompra":{"where":["id_socio_negocio","$id_socio_negocio"]}}]')
        ];
    }
}