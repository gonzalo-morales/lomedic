<?php
namespace App\Http\Models\Ventas;

use App\Http\Models\ModelCompany;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Finanzas\CondicionesPago;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\UsosCfdis;
use App\Http\Models\Administracion\Certificados;
use App\Http\Models\Administracion\MetodosPago;
use App\Http\Models\Administracion\TiposComprobantes;

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
    protected $primaryKey = 'id_documento';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'fk_id_socio_negocio', 'fk_id_proyecto', 'fk_id_empresa', 'fk_id_sucursal', 'fk_id_certificado', 'fk_id_tipo_comprobante',
        'fk_id_forma_pago', 'fk_id_metodo_pago', 'fk_id_uso_cfdi', 'fk_id_moneda', 'tipo_cambio', 'descuento', 'fk_id_serie',
        'subtotal', 'impuestos', 'total', 'serie', 'folio', 'fecha_creacion', 'fecha_vencimiento', 'fecha_timbrado', 'fk_id_estatus',
        'version_cdfi', 'certificado_sat', 'sello_sat', 'sello_cdfi', 'uuid', 'observaciones', 'fk_id_condicion_pago', 'total_pagado',
        'xml_original','xml_timbrado','cadena_original','version_tfd','codigo_qr'
    ];

    public $niceNames = [];
    
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

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];
    
    public function empresa()
    {
        return $this->hasOne(Empresas::class,'id_empresa','fk_id_empresa');
    }
    
    public function certificado()
    {
        return $this->hasOne(Certificados::class,'id_certificado','fk_id_certificado');
    }
    
    public function usocfdi()
    {
        return $this->hasOne(UsosCfdis::class,'id_uso_cfdi','fk_id_uso_cfdi');
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

    public function moneda()
    {
        return $this->hasOne(Monedas::class,'id_moneda','fk_id_moneda');
    }

    public function formapago()
    {
        return $this->hasOne(FormasPago::class,'id_forma_pago','fk_id_forma_pago');
    }
    
    public function metodopago()
    {
        return $this->hasOne(MetodosPago::class,'id_metodo_pago','fk_id_metodo_pago');
    }
    
    public function tipocomprobante()
    {
        return $this->hasOne(TiposComprobantes::class,'id_tipo_comprobante','fk_id_tipo_comprobante');
    }
    
    public function condicionpago()
    {
        return $this->hasOne(CondicionesPago::class,'id_condicion_pago','fk_id_condicion_pago');
    }

    public function detalle(){
        return $this->hasMany(FacturasClientesDetalle::class,'fk_id_documento','id_documento');
    }
    
    public function relaciones(){
        return $this->hasMany(CfdiRelacionesClientes::class,'fk_id_documento','id_documento')->where('fk_id_tipo_documento',$this->fk_id_tipo_documento);
    }

    public function toArrayWithDetails(){
        return [
            'id' => $this->id_documento
        ];
    }
}