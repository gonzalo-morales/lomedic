<?php
namespace App\Http\Models\Ventas;

use App\Http\Models\ModelCompany;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\Administracion\Localidades;
use App\Http\Models\Inventarios\SolicitudesSalidaDetalle;

class Pedidos extends ModelCompany
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'ven_opr_pedidos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_documento';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'fk_id_socio_negocio', 
        'fk_id_proyecto', 
        'fk_id_localidad', 
        'fk_id_sucursal', 
        'fk_id_condicion_pago',
        'fecha_necesaria', 
        'fecha_cancelacion',
        'motivo_cancelacion', 
        'fk_id_estatus', 
        'impuesto',
        'subtotal', 
        'total', 
        'descuento_general', 
        'descuento_total', 
        'fk_id_moneda',
        'fecha_limite',
        'fecha_pedido',
        'no_pedido'
    ];

    public $niceNames =[];

    protected $dataColumns = [];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_documento' => 'No. Pedido',
        'cliente.nombre_comercial' => 'Cliente',
        'proyecto.proyecto' => 'Proyecto',
        'localidad.localidad' => 'Localidad',
        'sucursal.sucursal' => 'Sucursal',
        'fecha_pedido'=>'Fecha Pedido',
        'fecha_limite' => 'Fecha Limite',
        'total' => 'Total',
        'moneda.descripcion' => 'Moneda',
        'estatus.estatus' => 'Estatus'
    ];

    public function localidad()
    {
        return $this->belongsTo(Localidades::class,'fk_id_localidad','id_localidad');
    }
    
    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class,'fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus');
    }

    public function cliente()
    {
        return $this->belongsTo(SociosNegocio::class,'fk_id_socio_negocio','id_socio_negocio');
    }
    
    public function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_proyecto','id_proyecto');
    }

    public function moneda()
    {
        return $this->hasOne(Monedas::class,'id_moneda','fk_id_moneda');
    }

    public function condicionpago()
    {
        return $this->hasOne(CondicionesPago::class,'id_condicion_pago','fk_id_condicion_pago');
    }

    public function detalle()
    {
        return $this->hasMany(PedidosDetalle::class,'fk_id_documento','id_documento');
    }
    
    public function anexos()
    {
        return $this->hasMany(PedidosAnexos::class,'fk_id_documento','id_documento');
    }
    
    public function solicitudes()
    {
        return $this->belongsTo(SolicitudesSalidaDetalle::class,'fk_id_documento','id_documento');
    }
    
    public function Pedidos()
    {
        return $this->morphedByMany(FacturasClientes::class);
    }
}