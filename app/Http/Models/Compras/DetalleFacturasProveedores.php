<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Compras\Ordenes;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Compras\SeguimientoDesviacion;
use App\Http\Models\Compras\DetalleSeguimientoDesviacion;
use App\Http\Models\ModelCompany;
use DB;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DetalleFacturasProveedores extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fac_det_facturas_proveedores';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_factura_proveedor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['importe',
        'precio_unitario',
        'descripcion',
        'fk_id_clave_unidad',
        'fk_id_clave_producto_servicio',
        'cantidad',
        'fk_id_impuesto',
        'descuento',
        'fk_id_orden_compra',
        'fk_id_detalle_orden_compra',
        'fk_id_nota_credito',
        'fk_id_factura_proveedor',
        'unidad'
    ];

    public $niceNames =[
        'precio_unitario'=>'precio unitario',
        'fk_id_clave_unidad'=>'clave unidad',
        'fk_id_clave_producto_servicio'=>'clave producto o servicio',
        'cantidad'=>'cantidad',
        'fk_id_impuesto'=>'impuesto',
        'fk_id_orden_compra'=>'orden de compra',
        'fk_id_nota_credito'=>'nota crédito',
    ];

    protected $dataColumns = [
//        'fk_id_estatus_oferta'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
//        'fk_id_sucursal'=>'required',
//        'fk_id_proveedor'=>'required',
//        'fk_id_moneda'=>'required',
//        'descuento_oferta'=>'nullable||regex:/^(\d{0,2}(\.\d{0,4})?\)$/'
    ];

    public static $idSeguimientoDesv ;
    public static $onlyOne = true;
    public static $counter = 0;

    public static function boot()
    {
        parent::boot();
        self::created(function($detalleFactura){

            // self::$counter++;
            // dump(self::$counter);

            // dd($detalleFactura::count());
            // $detallesFactura = FacturasProveedores::join('fac_det_facturas_proveedores','fac_opr_facturas_proveedores.id_factura_proveedor','=','fac_det_facturas_proveedores.fk_id_factura_proveedor')
            //                                         ->where('id_factura_proveedor','=',$detalleEntrada->entrada->referencia_documento)->where('fac_det_facturas_proveedores.fk_id_orden_compra','=',$detalleEntrada->entrada->numero_documento)
            //                                         ->groupBY('id_factura_proveedor','id_detalle_factura_proveedor')
            //                                         ->select('id_factura_proveedor','serie_factura','folio_factura','fk_id_socio_negocio','id_detalle_factura_proveedor','importe','cantidad','precio_unitario',
            //                                                 'fk_id_factura_proveedor','fk_id_orden_compra','fk_id_detalle_orden_compra')
            //                                         ->get();

            // dump(DetalleOrdenes::where('id_orden_detalle',$detalleFactura->fk_id_detalle_orden_compra)->get());
            $detallesOrden = DetalleOrdenes::where('id_orden_detalle',$detalleFactura->fk_id_detalle_orden_compra)->get();
            // dump(array('detallesOrden'=> $detallesOrden));
            // dump($detallesOrden);
            foreach ($detallesOrden as $detOrden) {

                if ($detOrden->precio_unitario != $detalleFactura->precio_unitario ) {
                    if (self::$onlyOne) {
                        $segDesv  = new SeguimientoDesviacion();
                        $segDesv->fk_id_proveedor = $detalleFactura->facturaProveedor->fk_id_socio_negocio;
                        $segDesv->serie_factura = $detalleFactura->facturaProveedor->serie_factura;
                        $segDesv->folio_factura = $detalleFactura->facturaProveedor->folio_factura;
                        $segDesv->fecha_captura = Carbon::now();
                        $segDesv->fk_id_usuario_captura = Auth::id();
                        $segDesv->estatus = 1;
                        $segDesv->tipo = 2;
                        $segDesv->save();

                        self::$idSeguimientoDesv = $segDesv->getKey();
                        self::$onlyOne = false;
                        // echo self::$onlyOne;
                    }
                    $detSegDesv = new DetalleSeguimientoDesviacion();

                    // dump(array('detOrden'=> $detOrden,'detlFactura'=> $detalleFactura));

                    $detSegDesv->precio_desviacion                  = $detalleFactura->precio_unitario - $detOrden->precio_unitario;
                    $detSegDesv->precio_factura                     = $detalleFactura->precio_unitario;
                    $detSegDesv->precio_orden_compra                = $detOrden->precio_unitario;
                    $detSegDesv->fk_id_orden_compra                 = $detOrden->orden->id_orden;
                    $detSegDesv->fk_id_detalle_orden_compra         = $detOrden->id_orden_detalle;
                    $detSegDesv->fk_id_seguimiento_desviacion       = self::$idSeguimientoDesv;
                    $detSegDesv->fk_id_factura_proveedor            = $detalleFactura->fk_id_factura_proveedor;
                    $detSegDesv->fk_id_detalle_factura_proveedor    = $detalleFactura->id_detalle_factura_proveedor;
                    $detSegDesv->save();
                }
            }
            // if (self::$counter == 2) {
            //     dd($detSegDesv);
            // }
            // $detallesOrden = Ordenes::join('com_det_ordenes','com_opr_ordenes.id_orden','=','com_det_ordenes.fk_id_documento')
                                        // ->where('com_det_ordenes.fk_id_documento',$detalleFactura->fk_id_orden_compra)
                                        // ->where('id_orden',$detalleFactura->orden->proveedor->fk_id_socio_negocio)
                                        // ->select('com_det_ordenes.*')
                                        // ->get();
            // dd($detallesOrden);
        });
    }

    public function clave_unidad()
    {
        return $this->hasOne(ClavesUnidades::class,'id_clave_unidad','fk_id_clave_unidad');
    }

    public function clave_producto_servicio()
    {
        return $this->hasOne(ClavesProductosServicios::class,'id_clave_producto_servicio','fk_id_clave_producto_servicio');
    }

    public function impuesto()
    {
        return $this->hasOne(Impuestos::class,'id_impuesto','fk_id_impuesto');
    }

    public function orden()
    {
        return $this->hasOne(Ordenes::class,'id_orden','fk_id_orden_compra');
    }
    public function facturaProveedor()
    {
        return $this->belongsTo(FacturasProveedores::class,'fk_id_factura_proveedor','id_factura_proveedor');
    }
}
