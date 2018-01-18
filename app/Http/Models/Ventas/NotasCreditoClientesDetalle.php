<?php
namespace App\Http\Models\Ventas;

use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\Impuestos;

class NotasCreditoClientesDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'fac_det_notas_credito_clientes';

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
        'fk_id_documento','fk_id_clave_producto_servicio','fk_id_sku','fk_id_upc','fk_id_clave_cliente','descripcion',
        'fk_id_unidad_medida','unidad','cantidad','precio_unitario','importe','eliminar','fk_id_moneda','fk_id_impuesto',
        'descuento','pedimento','cuenta_predial','impuesto','fk_id_documento_relacionado','fk_id_tipo_documento_relacionado'
    ];

    public $niceNames =[];

    protected $dataColumns = [];
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
    public $rules = [];
    
    public function nota()
    {
        return $this->hasOne(NotasCreditoClientes::class,'id_documento','fk_id_documento');
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
        return $this->hasOne(ClaveClienteProductos::class,'id_clave_cliente_producto','fk_id_clave_cliente');
    }
    
    public function unidadmedida()
    {
        return $this->hasOne(ClavesUnidades::class,'id_clave_unidad','fk_id_unidad_medida');
    }
    
    public function impuestos()
    {
        return $this->hasOne(Impuestos::class,'id_impuesto','fk_id_impuesto');
    }

    public function moneda()
    {
        return $this->hasOne(Monedas::class,'id_moneda','fk_id_moneda');
    }

    public function documentobase()
    {
        switch($this->fk_id_tipo_documento_base)
        {
            case 4://Factura
                return $this->hasOne(FacturasClientes::class,'id_documento','fk_id_documento_base');
                break;
            case 6://Nota Cargo
                return $this->hasOne(NotasCargoClientes::class,'id_documento','fk_id_documento_base');
                break;
        }
    }
}