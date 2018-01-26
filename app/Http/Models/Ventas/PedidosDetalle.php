<?php
namespace App\Http\Models\Ventas;

use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\TiposDocumentos;
use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\Impuestos;

class PedidosDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'ven_det_pedidos';
    
    protected $morphClass = 'PedidoCliente';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_documento_detalle';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'fk_id_sku', 'fk_id_upc', 'fk_id_proyecto', 'precio_unitario', 'cantidad', 'fk_id_impuesto', 'cerrado',
        'fk_id_documento', 'descuento', 'fk_id_tipo_documento', 'fk_id_clave_cliente_producto','fk_id_documento_base',
        'fk_id_tipo_documento_base','fk_id_linea'
    ];

    public $niceNames =[];

    protected $dataColumns = [];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];
    
    public function pedido()
    {
        return $this->hasOne(Pedidos::class,'id_documento','fk_id_documento');
    }
    
    public function tipodocumento()
    {
        return $this->hasOne(TiposDocumentos::class,'id_tipo_documento','fk_id_tipo_documento');
    }
    
    public function claveproducto()
    {
        return $this->hasOne(ClavesProductosServicios::class,'id_clave_producto_servicio','fk_id_clave_producto_servicio');
    }
    
    public function sku()
    {
        return $this->hasOne(Productos::class,'id_sku','fk_id_sku');
    }
    
    public function upc()
    {
        return $this->hasOne(Upcs::class,'id_upc','fk_id_upc');
    }
    
    public function clavecliente()
    {
        return $this->hasOne(ClaveClienteProductos::class,'id_clave_cliente_producto','fk_id_clave_cliente_producto');
    }
    
    public function unidadmedida()
    {
        return $this->hasOne(ClavesUnidades::class,'id_clave_unidad','fk_id_unidad_medida');
    }
    
    public function impuesto()
    {
        return $this->hasOne(Impuestos::class,'id_impuesto','fk_id_impuesto');
    }

    public function moneda()
    {
        return $this->hasOne(Monedas::class,'id_moneda','fk_id_moneda');
    }
}