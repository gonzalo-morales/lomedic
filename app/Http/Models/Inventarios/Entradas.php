<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\SeguimientoDesviacion;
use App\Http\Models\Compras\DetalleSeguimientoDesviacion;
use App\Http\Models\Compras\FacturasProveedores;
use App\Http\Models\Compras\DetalleFacturasProveedores;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Http\Models\ModelCompany;
use DB;
use App\Scopes\AgeScope;

class Entradas extends ModelCompany
{

    protected $table = 'inv_opr_entrada_almacen';

    protected $primaryKey = 'id_documento';

    protected $fillable = [
        'fk_id_tipo_documento',
        'numero_documento',
        'referencia_documento',
        'fecha_entrada',
        'fk_id_estatus_entrada',
        'fk_id_almacen',
        'fk_id_ubicacion',
        'no_poliza'
    ];

    protected $fields = [
        'id_documento' => 'Número Entrada',
        'tipoDocumento.nombre_documento' => 'Tipo documento',
        'numero_documento' => 'Numero de documento',
        'fecha_entrada' => 'Fecha de entrada',
        'referencia_documento' => 'Referencia del documentos',
        'estatus_documento_span' => 'Estatus de la entrada'
    ];

    /*public static function boot()
    {
        parent::boot();
        self::created(function($entrada){
            // $entrada->$entrada->getKey();
            $detallesEntrada = Entradas::where('id_entrada_almacen',$entrada->getKey())->get();
            print_r($detallesEntrada);
            // $entrada->id_entrada_almacen;
            // $entrada->referencia_documento;

            // $detalleFacturas = FacturasProveedores::where('id_factura_proveedor',$entrada->referencia_documento)->get();
            // $detallesFactura = FacturasProveedores::join('fac_det_facturas_proveedores','fac_opr_facturas_proveedores.id_factura_proveedor','=','fac_det_facturas_proveedores.fk_id_factura_proveedor')
            //                                         ->where('id_factura_proveedor','=',$entrada->referencia_documento)->where('fac_det_facturas_proveedores.fk_id_orden_compra','=',$entrada->numero_documento)
            //                                         ->groupBY('id_factura_proveedor','id_detalle_factura_proveedor')
            //                                         ->select('id_factura_proveedor','serie_factura','folio_factura','fk_id_socio_negocio','id_detalle_factura_proveedor','importe','cantidad','precio_unitario',
            //                                                 'fk_id_factura_proveedor','fk_id_orden_compra','fk_id_detalle_orden_compra')
            //                                         ->get();
            // print_r($detallesFactura);

            $detallesOrden = Ordenes::join('com_det_ordenes','com_opr_ordenes.id_orden','=','com_det_ordenes.fk_id_documento')
                                        ->where('id_orden',$entrada->numero_documento)
                                        ->select('com_det_ordenes.*')
                                        ->get();
            // print_r($detallesOrden);

            $segDesv  = new SeguimientoDesviacion();
            $segDesv->fk_id_proveedor = 10;
            // $segDesv->serie_factura = 'A';
            // $segDesv->folio_factura = 19;
            $segDesv->fecha_captura = Carbon::now();
            $segDesv->fk_id_usuario_captura = Auth::id();
            $segDesv->estatus = 1;
            $segDesv->tipo = 1;
            // $segDesv->save();
            // print_r($segDesv);
            foreach ($entrada->detalleEntrada as $detEntrada) {
                print_r($detEntrada);
                $detSegDesv = new DetalleSeguimientoDesviacion();
                foreach ($detallesOrden as $detOrden) {
                    echo $detEntrada->cantidad_surtida ." != ". $detOrden->cantidad;
                    if ($detEntrada->cantidad_surtida != $detOrden->cantidad) {
                        $detSegDesv->cantidad_desviacion            = abs($detEntrada->cantidad_surtida - $detOrden->cantidad);
                        $detSegDesv->cantidad_entrada               = $detEntrada->cantidad_surtida;
                        $detSegDesv->cantidad_orden_compra          = $detOrden->cantidad;
                        $detSegDesv->fk_id_seguimiento_desviacion   = $segDesv->id;
                        $detSegDesv->fk_id_orden_compra             = $detOrden->fk_id_documento;
                        $detSegDesv->fk_id_detalle_orden_compra     = $detOrden->id_orden;
                        $detSegDesv->fk_id_entrada                  = $entrada->id_entrada_almacen;
                        $detSegDesv->fk_id_detalle_entrada          = $detEntrada->id_detalle_entrada;
                    }

                    print_r($detSegDesv);

                    // if ($detEntrada->precio_unitario != $detOrden->precio_unitario) {
                    //     $detSegDesv->cantidad_desviacion    = abs($detEntrada->cantidad - $detOrden->cantidad);
                    //     $detSegDesv->cantidad_entrada       = $detEntrada->cantidad;
                    //     $detSegDesv->cantidad_orden_compra  = $detOrden->cantidad;
                    // }
                }
            }

        });
    }*/

    public function detalleEntrada()
    {
        return $this->hasMany('App\Http\Models\Inventarios\EntradaDetalle','fk_id_entrada_almacen', 'id_entrada_almacen');
    }
//
//    public function datosEntrada($id,$tipo_documento)
//    {
//        switch ($tipo_documento)
//        {
//            case 1:
//                break;
//            case 2:
//                breack;
//            case 3:
//                $documento = Ordenes::where('id_orden',$id)->first();
//                break;
//        }
//
//        return $documento;
//    }

    public function sucursales()
    {
        return $this->hasOne('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }
    // public function proveedor()
    // {
    //     return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_socio_negocio');
    // }
    public function tipoDocumento()
    {
        return $this->belongsTo('App\Http\Models\Administracion\TiposDocumentos','fk_id_tipo_documento','id_tipo_documento');
    }
    public function productos()
    {
        return $this->hasMany('App\Http\Models\Inventarios\EntradaDetalle','fk_id_entrada_almacen', 'id_entrada_almacen');
    }
    public function facturaProveedor()
    {
        return $this->belongsTo('App\Http\Models\Compras\FacturasProveedores','referencia_documento', 'id_factura_proveedor');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_entrada');
    }
    public function detalles()
    {
        return $this->hasMany(EntradaDetalle::class,'fk_id_documento','id_documento');
    }


}
