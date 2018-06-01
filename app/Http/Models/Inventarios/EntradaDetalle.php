<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 13/11/2017
 * Time: 11:37
 */


namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Compras\SeguimientoDesviacion;
use App\Http\Models\Compras\DetalleSeguimientoDesviacion;
use App\Http\Models\Compras\FacturasProveedores;
use App\Http\Models\Compras\DetalleFacturasProveedores;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use DB;

class EntradaDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_det_entrada_almacen';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_documento_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_documento',
        'fk_id_tipo_documento',
        'fk_id_upc',
        'cantidad_surtida',
        'fecha_caducidad',
        'lote',
        'fk_id_linea',
        'costo_unitario',
        'fk_id_proyecto',
        'fk_id_tipo_documento_base'
    ];

//    public $niceNames =[];
//
//    protected $dataColumns = [];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_documento_detalle' => '#',
        'fk_id_documento' => 'Numero entrada',
        'lote' => 'Numero de lote',
        'fk_id_sku' => 'Sku',
        'fk_id_upc' => 'Upc',
        'cantidad_surtida' => 'Cantidad surtida'
    ];
    public static $idSeguimientoDesv ;
    public static $onlyOne = true;

/*    public static function boot()
    {
        parent::boot();
        self::created(function($detalleEntrada){
            // $detalleFacturas = FacturasProveedores::where('id_factura_proveedor',$entrada->referencia_documento)->get();
            // $detallesFactura = FacturasProveedores::join('fac_det_facturas_proveedores','fac_opr_facturas_proveedores.id_factura_proveedor','=','fac_det_facturas_proveedores.fk_id_factura_proveedor')
            //                                         ->where('id_factura_proveedor','=',$detalleEntrada->entrada->referencia_documento)
            //                                         ->where('fac_det_facturas_proveedores.fk_id_orden_compra','=',$detalleEntrada->entrada->numero_documento)
            //                                         ->groupBY('id_factura_proveedor','id_detalle_factura_proveedor')
            //                                         ->select('id_factura_proveedor','serie_factura','folio_factura','fk_id_socio_negocio','id_detalle_factura_proveedor','importe','cantidad','precio_unitario',
            //                                                 'fk_id_factura_proveedor','fk_id_orden_compra','fk_id_detalle_orden_compra')
            //                                         ->get();
            //
            // print_r($detallesFactura);

            $detallesOrden = Ordenes::join('com_det_ordenes','com_opr_ordenes.id_orden','=','com_det_ordenes.fk_id_documento')
                                        ->where('id_orden',$detalleEntrada->entrada->numero_documento)
                                        ->select('com_det_ordenes.*')
                                        ->get();

                foreach ($detallesOrden as $detOrden) {
                    if ($detalleEntrada->fk_id_sku == $detOrden->fk_id_sku && $detalleEntrada->fk_id_upc == $detOrden->fk_id_upc) {
                        if ($detalleEntrada->cantidad_surtida != $detOrden->cantidad) {
                            if (self::$onlyOne) {
                                $segDesv  = new SeguimientoDesviacion();
                                $segDesv->fk_id_proveedor = $detalleEntrada->entrada->facturaProveedor->fk_id_socio_negocio;
                                // print_r($detOrden->orden);
                                $segDesv->serie_factura = $detalleEntrada->entrada->facturaProveedor->serie_factura;
                                $segDesv->folio_factura = $detalleEntrada->entrada->facturaProveedor->folio_factura;
                                $segDesv->fecha_captura = Carbon::now();
                                $segDesv->fk_id_usuario_captura = Auth::id();
                                $segDesv->estatus = 2;
                                $segDesv->tipo = 1;
                                $segDesv->save();

                                self::$idSeguimientoDesv = $segDesv->getKey();
                                self::$onlyOne = false;
                            }
                            // $searchDesviacion = SeguimientoDesviacion::where('fk_id_proveedor', $detalleEntrada->entrada->facturaProveedor->fk_id_socio_negocio);

                            $detSegDesv = new DetalleSeguimientoDesviacion();

                            $detSegDesv->cantidad_desviacion            = abs($detalleEntrada->cantidad_surtida - $detOrden->cantidad);
                            $detSegDesv->cantidad_entrada               = $detalleEntrada->cantidad_surtida;
                            $detSegDesv->cantidad_orden_compra          = $detOrden->cantidad;
                            $detSegDesv->fk_id_seguimiento_desviacion   = self::$idSeguimientoDesv;
                            $detSegDesv->fk_id_orden_compra             = $detOrden->fk_id_documento;
                            $detSegDesv->fk_id_detalle_orden_compra     = $detOrden->id_orden_detalle;
                            $detSegDesv->fk_id_entrada                  = $detalleEntrada->entrada->id_entrada_almacen;
                            $detSegDesv->fk_id_detalle_entrada          = $detalleEntrada->id_detalle_entrada;

                            $detSegDesv->save();
                        }
                        // print_r($detSegDesv);
                    }
                }

        });
    }*/

    // public function sucursales()
    // {
    //     return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucurñsal','id_sucursal');
    // }

    // public function estatus()
    // {
    //     return $this->hasOne('App\Http\Models\Compras\EstatusSolicitudes','id_estatus','fk_id_estatus_orden');
    // }

    // public function empresa()
    // {
    //     return $this->belongsTo('App\Http\Models\Administracion\Empresas','fk_id_empresa','id_empresa');
    // }

    // public function tipoEntrega()
    // {
    //     return $this->hasOne('App\Http\Models\SociosNegocio\TiposEntrega','id_tipo_entrega','fk_id_tipo_entrega');
    // }

    // public function proveedor()
    // {
    //     return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_socio_negocio');
    // }

    // public function detalleOrdenes()
    // {
    //     return $this->hasMany('App\Http\Models\Compras\DetalleOrdenes','fk_id_orden', 'id_orden');
    // }

    public function sku()
    {
        return $this->hasOne('App\Http\Models\Inventarios\Productos','id_sku','fk_id_sku');
    }

    public function upc()
    {
        return $this->hasOne('App\Http\Models\Inventarios\Upcs','id_upc','fk_id_upc');
    }
    public function entrada()
    {
        return $this->belongsTo('App\Http\Models\Inventarios\Entradas','fk_id_entrada_almacen','id_entrada_almacen');
    }

}
