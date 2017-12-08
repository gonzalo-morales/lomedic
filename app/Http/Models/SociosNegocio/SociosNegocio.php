<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Empresas;
use App\Http\Models\RecursosHumanos\Empleados;

class SociosNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'maestro.gen_cat_socios_negocio';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_socio_negocio';
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fk_id_tipo_socio','fk_id_forma_pago','fk_id_tipo_entrega','fk_id_sucursal_entrega','fk_id_usuario_modificacion',
    'razon_social','rfc','nombre_comercial','telefono','sitio_web','monto_credito','dias_credito','monto_minimo_facturacion','fecha_modificacion',
    'ejecutivo_venta','fk_id_ramo','fk_id_pais_origen','fk_id_moneda','activo'];
    
    protected $eagerLoaders = ['ramo','tiposocioventa','tiposociocompra'];
    
    protected $fields = [
        'razon_social' => 'Razon Social',
        'rfc' => 'RFC',
        'nombre_comercial' => 'Nombre Comercial',
        'ramo.ramo' => 'Ramo',
        'tipos_socios' => 'Tipo Socio',
        'activo_text' => 'Estatus'
    ];
    
    public $rules = [ /*
        'razon_social' => 'required|min:5',
        'rfc' => 'required',
        'nombre_comercial' => 'required',
        'ejecutivo_venta' => 'required',
        'telefono' => 'required',
        'sitio_web' => 'required',
        'ramo' => 'required',
        'pais_origen' => 'required',
        'tipo_socio' => 'required',
        'moneda' => 'required',
        #'empresas' => 'required',
        'condiciones_pago.monto_credito' => 'required',
        'condiciones_pago.dias_credito' => 'required',
        'condiciones_pago.forma_pago' => 'required',
        'condiciones_pago.cuentas' => 'required',*/
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
}