<?php

namespace App\Http\Controllers\Compras;

use App\Http\Controllers\ControllerBase;
use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Compras\FacturasProveedores;
use App\Http\Models\Compras\Pagos;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PagosController extends ControllerBase
{
	public function __construct(Pagos $entity)
	{
		$this->entity = $entity;
	}

	public function getDataView($entity = null)
    {
        return [
            'bancos'=>Bancos::where('activo','f')->pluck('banco','id_banco'),
            'formas_pago'=>FormasPago::select('id_forma_pago',DB::raw("concat('(',forma_pago,') ',descripcion) as text"))->where('activo','t')->pluck('text','id_forma_pago'),
            'monedas'=>Monedas::select(DB::raw("concat('(',moneda,') ',descripcion) as text"),'id_moneda')->where('activo','t')->pluck('text','id_moneda'),
            'facturas'=>FacturasProveedores::select('id_factura_proveedor')->where('fk_id_estatus_factura',1)->pluck('id_factura_proveedor','id_factura_proveedor'),
//            'solicitudes'=>Solicitudes
        ];
    }

    public function destroy(Request $request, $company, $idOrIds)
    {
        DB::beginTransaction();
        $isSuccess = $this->entity->where($this->entity->getKeyName(), [$idOrIds])->update(['eliminar' => 't','activo'=>'f']);
        if ($isSuccess) {

            DB::commit();
            $this->log('destroy', $idOrIds);

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
            $this->log('error_destroy', $idOrIds);

            if ($request->ajax()) {
                # Respuesta Json
                return ['success' => false];
            } else {
                return $this->redirect('error_destroy');
            }
        }
    }
}