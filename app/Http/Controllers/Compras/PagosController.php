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
	public function __construct(Pagos $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {
        return [
            'bancos'=>Bancos::activos()->orderBy('banco')->pluck('banco','id_banco'),
            'formas_pago' => FormasPago::selectRaw("CONCAT(forma_pago,' - ',descripcion) as forma_pago, id_forma_pago")->activos()->orderBy('forma_pago')->pluck('forma_pago','id_forma_pago'),
            'monedas' => Monedas::selectRaw("CONCAT(descripcion,' (',moneda,')') as moneda, id_moneda")->activos()->orderBy('moneda')->pluck('moneda','id_moneda'),
            'facturas'=>FacturasProveedores::select('id_factura_proveedor')->where('fk_id_estatus_factura',1)->where('total','>',0)->pluck('id_factura_proveedor','id_factura_proveedor')->prepend('...',0),
            'solicitudes'=>SolicitudesPagos::where('fk_id_estatus_solicitud_pago',1)->where('total','>',0)->whereHas('detalle')->pluck('id_solicitud_pago','id_solicitud_pago')->prepend('...',0),
            'js_factura'=>Crypt::encryptString('"select":["total","total_pagado"],"conditions":[{"where":["id_factura_proveedor",$fk_id_documento]}]'),
            'js_solicitud'=>Crypt::encryptString('"select":["total","total_pagado"],"conditions":[{"where":["id_solicitud_pago",$fk_id_documento]}]'),
        ];
    }

    public function store(Request $request, $company, $compact = false)
    {
        $myfile = $request->file('comprobante_input');
        $fileName = str_replace([':',' '],['-','_'],Carbon::now()->toDateTimeString().' '.$myfile->getClientOriginalName());
        Storage::disk('pagos')->put($company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName, file_get_contents($myfile->getRealPath()));
        $request->request->set('comprobante',$company.'/'.Carbon::now()->year.'/'.Carbon::now()->month.'/'.$fileName, file_get_contents($myfile->getRealPath()));
        return parent::store($request, $company, $compact);
    }

    public function destroy(Request $request, $company, $idOrIds, $attributes = [])
    {
        DB::beginTransaction();
        $isSuccess = $this->entity->where($this->entity->getKeyName(), [$idOrIds])->update(['eliminar' => 't']);
        if ($isSuccess) {

            DB::commit();
            #$this->log('destroy', $idOrIds);

            # Eliminamos cache
            Cache::tags(getCacheTag('index'))->flush();

            if ($request->ajax()) {
                # Respuesta Json
                return ['success' => true];
            } else {
                return $this->redirect('destroy');
            }

        } else {

            DB::rollBack();
            #$this->log('error_destroy', $idOrIds);

            if ($request->ajax()) {
                # Respuesta Json
                return ['success' => false];
            } else {
                return $this->redirect('error_destroy');
            }
        }
    }
}