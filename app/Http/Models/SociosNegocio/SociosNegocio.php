<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Finanzas\CuentasContables;
use App\Http\Models\ModelBase;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\Administracion\Afiliaciones;
use App\Http\Models\Administracion\Diagnosticos;
use App\Http\Models\Administracion\Medicos;

class SociosNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'gen_cat_socios_negocio';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_socio_negocio';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'fk_id_forma_pago','fk_id_tipo_entrega','razon_social','rfc','nombre_comercial','telefono','sitio_web','monto_credito','dias_credito','monto_minimo_facturacion',
        'activo','fk_id_ramo','fk_id_pais_origen','fk_id_moneda','tiempo_entrega','fk_id_condicion_pago','fk_id_metodo_pago','fk_id_tipo_proveedor','fk_id_pago_paqueteria',
        'fk_id_ejecutivo_venta','fk_id_tipo_socio_venta','fk_id_tipo_socio_compra','activo_desde','activo_hasta','fk_id_ejecutivo_compra','interes_retraso','fk_id_cuenta_cliente',
        'fk_id_cuenta_proveedor'];

    protected $fields = [
        'razon_social' => 'Razon Social',
        'rfc' => 'RFC',
        'nombre_comercial' => 'Nombre Comercial',
        'ramo.ramo' => 'Ramo',
        'tipos_socios' => 'Tipo Socio',
        'activo_span' => 'Estatus'
    ];

    public $niceNames = [];

    public function getTiposSociosAttribute()
    {
        $venta = $this->tiposocioventa->tipo_socio ?? '';
        $compra = $this->tiposociocompra->tipo_socio ?? '';
        $compra = !empty($venta) && !empty($compra) ? ", ".$compra : (!empty($compra) ? $compra : '');
        return trim($venta.$compra);
    }
    public function empresas(){
        return $this->belongsToMany(Empresas::class,'sng_det_empresa_socio_negocio','fk_id_socio_negocio','fk_id_empresa');
    }
    public function contactos(){
        return $this->hasMany(ContactosSociosNegocio::class,'fk_id_socio_negocio');
    }
    public function direcciones(){
        return $this->hasMany(DireccionesSociosNegocio::class,'fk_id_socio_negocio');
    }
    public function formaspago(){
        return $this->belongsToMany(FormasPago::class,'sng_det_forma_pago_socio_negocio','fk_id_socio_negocio','fk_id_forma_pago');
    }
    public function cuentas(){
        return $this->hasMany(CuentasSociosNegocio::class,'fk_id_socio_negocio');
    }
    public function anexos(){
        return $this->hasMany(AnexosSociosNegocio::class,'fk_id_socio_negocio');
    }
    public function productos(){
        return $this->hasMany(ProductosSociosNegocio::class,'fk_id_socio_negocio');
    }
    public function sucursal(){
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal_entrega');
    }
    public function usuario(){
        return $this->belongsTo('App\Http\Models\Administracion\Usuarios','fk_id_usuario_modificacion');
    }
    public function ramo(){
        return $this->belongsTo(RamosSocioNegocio::class,'fk_id_ramo');
    }
    public function tiposocioventa(){
        return $this->belongsTo(TiposSocioNegocio::class,'fk_id_tipo_socio_venta');
    }
    public function tiposociocompra(){
        return $this->belongsTo(TiposSocioNegocio::class,'fk_id_tipo_socio_compra');
    }
//    public function tipoproveedor(){
//        return $this->belongsTo(TiposProveedores::class,'fk_id_tipo_proveedor');
//    }
    public function ejecutivocompra(){
        return $this->hasOne(Empleados::class,'id_empleado','fk_id_ejecutivo_compra');
    }

    /**
     * Obtenemos proyectos relacionados
     * @return @hasMany
     */
    public function proyectos()
    {
        return $this->hasMany(Proyectos::class, 'fk_id_cliente', 'id_socio_negocio');
    }

    public function pais()
    {
        return $this->hasOne(Paises::class,'id_pais','fk_id_pais');
    }

    public function sucursalcliente()
    {
        return $this->hasOne(Sucursales::class,'fk_id_cliente','id_socio_negocio');
    }

    public function cuentaproveedor()
    {
        return $this->hasOne(CuentasContables::class,'id_cuenta_contable','fk_id_cuenta_proveedor');
    }

    public function cuentacliente()
    {
        return $this->hasOne(CuentasContables::class,'id_cuenta_contable','fk_id_cuenta_cliente');
    }

    public function afiliados()
    {
        return $this->hasMany(Afiliaciones::class, 'fk_id_cliente','id_socio_negocio');
    }

    public function diagnosticos()
    {
        return $this->hasMany(Diagnosticos::class,'fk_id_cliente','id_socio_negocio');
    }

    public function medico(){
        return $this->belongsToMany(Medicos::class,'gen_det_medico_cliente','fk_id_medico','fk_id_cliente');
    }
}