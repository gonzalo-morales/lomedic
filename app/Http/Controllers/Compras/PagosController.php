<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Compras\FacturasProveedores;
use App\Http\Models\Compras\Pagos;
use App\Http\Models\Compras\SolicitudesPagos;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class PagosController extends ControllerBase
{
	public function __construct()
	{
		$this->entity = new Pagos;
	}

	public function getDataView($entity = null)
    {
        return [
            'bancos'=>Bancos::where('activo',1)->orderBy('banco')->pluck('banco','id_banco'),
            'formas_pago' => FormasPago::selectRaw("CONCAT(forma_pago,' - ',descripcion) as forma_pago, id_forma_pago")->where('activo',1)->orderBy('forma_pago')->pluck('forma_pago','id_forma_pago'),
            'monedas' => Monedas::selectRaw("CONCAT(descripcion,' (',moneda,')') as moneda, id_moneda")->where('activo',1)->orderBy('moneda')->pluck('moneda','id_moneda'),
            'facturas'=>FacturasProveedores::select('id_documento')->where('fk_id_estatus_factura',1)->where('total','>',0)->pluck('id_documento','id_documento')->prepend('...',0),
            'solicitudes'=>SolicitudesPagos::where('fk_id_estatus_solicitud_pago',1)->where('total','>',0)->whereHas('detalle')->pluck('id_documento','id_documento')->prepend('...',0),
            'js_factura'=>Crypt::encryptString('"select":["total","total_pagado"],"conditions":[{"where":["id_documento",$fk_id_documento]}]'),
            'js_solicitud'=>Crypt::encryptString('"select":["total","total_pagado"],"conditions":[{"where":["id_documento",$fk_id_documento]}]'),
        ];
    }

    public function store(Request $request, $company, $compact = false)
    {
        $myfile = $request->file('comprobante_input');
        $fileName = utf8_encode(str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName()));
        Storage::disk('pagos')->put($company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName, file_get_contents($myfile->getRealPath()));
        $request->request->set('comprobante',$company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName, file_get_contents($myfile->getRealPath()));
        return parent::store($request, $company, $compact);
    }

    public function descargaComprobante(Request $request,$company,$id){
	    $pago = Pagos::find($id);
	    return Storage::disk('pagos')->download($pago->comprobante);
    }
}