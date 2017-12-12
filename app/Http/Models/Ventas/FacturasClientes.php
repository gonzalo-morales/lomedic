<?php
namespace App\Http\Models\Ventas;

use App\Http\Models\ModelCompany;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\FormasPago;
use DB;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\EstatusDocumentos;

class FacturasClientes extends ModelCompany
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'fac_opr_facturas_clientes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_factura';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [];

    public $niceNames =[];

    protected $dataColumns = [];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'serie' => 'Serie',
        'folio' => 'Folio',
        'cliente.nombre_comercial' => 'Cliente',
        'sucursal.sucursal' => 'Sucursal',
        'fecha_creacion'=>'Fecha creación',
        'fecha_vencimiento' => 'Vigencia',
        'moneda.descripcion' => 'Moneda',
        'total' => 'Total',
        'total_pagado' => 'Total Pagado',
        'estatus.estatus' => 'Estatus'
    ];

    protected $eagerLoaders = [];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];

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

    public function moneda()
    {
        return $this->hasOne(Monedas::class,'id_moneda','fk_id_moneda');
    }

    public function formapago()
    {
        return $this->hasOne(FormasPago::class,'id_forma_pago','fk_id_forma_pago');
    }

    public function detallefactura(){
        return $this->hasMany(DetalleFacturasProveedores::class,'fk_id_factura_proveedor','id_factura_proveedor');
    }
}