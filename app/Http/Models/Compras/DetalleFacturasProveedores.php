<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\ModelCompany;
use DB;

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
}
