<?php

namespace App\Http\Controllers\RestApi;

use App\Http\Controllers\Controller;
use App\Http\Models\Ventas\FacturasClientes;
use Illuminate\Http\Request;

class FacturasClientesController extends Controller
{
    function __construct(FacturasClientes $entity)
    {
        $this->middleware('auth.api');
    }

    public function index()
    {
        //Trae las facturas sin póliza o movimiento asociado que su total sea mayor a cero y que esté timbrada
        $facturas = FacturasClientes::whereNull('no_poliza')->whereNull('no_movimiento')->where('total','>',0)
            ->whereNotNull('uuid')->orderBy('id_documento')->get();
        $facturas->map(function($factura){
            return $factura->relaciones;
        });
        $facturas->map(function ($factura){
            $return = collect();
            $return->cliente = $factura->cliente;
            if(isset($factura->cliente->cuentacliente))
                $return->cuenta = $factura->cliente->cuentacliente;
            if(isset($factura->cliente->cuentacliente->padre))
                $return->padre = $factura->cliente->cuentacliente->padre;
            if(isset($factura->cliente->cuentacliente->agrupadorcuenta))
                $return->agrupadorsat = $factura->cliente->cuentacliente->agrupadorcuenta;
           return $return;
        });
        foreach ($facturas as $factura){
            $factura->relaciones->map(function ($relacion){
                return $relacion->documento;
            });
        }
        return response()->json(['status'=>200,'data'=>$facturas], 200);
    }

    public function store(Request $request)
    {
        foreach ($request->poliza['movimientos'] as $movimiento){
            $factura = FacturasClientes::find($movimiento['id_documento']);
            $factura->no_poliza = $request->poliza['id_poliza'];//Es la llave primaria de la póliza
            $factura->no_movimiento = $movimiento['id_movimiento'];//Número de movimiento en la póliza
            $factura->ejercicio = $request->poliza['ejercicio'];
            $factura->periodo = $request->poliza['periodo'];
            $factura->guid_movimiento = $movimiento['guid'];
            $factura->save();
        }

        return response()->json(["status"=>201,'data'=>'Facturas de cliente actualizadas'],201);
    }
}