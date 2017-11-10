<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;
use DB;

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
    'razon_social','rfc','nombre_corto','telefono','sitio_web','monto_credito','dias_credito','monto_minimo_facturacion','fecha_modificacion',
    'ejecutivo_venta','fk_id_ramo','fk_id_pais_origen','fk_id_moneda','activo'];
    
    protected $eagerLoaders = ['ramo','tiposocio'];
    
    protected $fields = [
        'razon_social' => 'Razon Social',
        'rfc' => 'RFC',
        'nombre_corto' => 'Nombre Comercial',
        'ramo.ramo' => 'Ramo',
        'tipos_socios' => 'Tipo Socio',
        'activo_text' => 'Estatus'
    ];
    
    public $rules = [
        'razon_social' => 'required|min:10',
        'rfc' => 'required',
        'nombre_corto' => 'required',
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
        'condiciones_pago.cuentas' => 'required',
        'info_entrega.tipos_entrega' => 'required',
        'info_entrega.sucursal' => "required_if:info_entrega.tipos_entrega,==,1", // id=> 1 : para "sucursal"
        'info_entrega.monto_minimo_facturacion' => 'required|numeric|min:1',
        'info_entrega.correos' => 'required',
        'info_entrega.correos.*.correo' => 'email',
    ];
    
    public function getTiposSociosAttribute()
    {
        return implode(', ',$this->tiposocio()->pluck('tipo_socio')->toarray());
    }
    
    /*
    const UPDATED_AT = 'fecha_modificacion';
    
    public function setUpdatedAtAttribute($value) {
        $this->attributes['fecha_modificacion'] = \Carbon\Carbon::now();
    }
    */
    
    public function formaPago(){
        return $this->belongsTo('App\Http\Models\Administracion\FormasPago','fk_id_forma_pago');
    }
    public function tipoEntrega(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\TiposEntrega','fk_id_tipo_entrega');
    }
    public function sucursal(){
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal_entrega');
    }
    public function usuario(){
        return $this->belongsTo('App\Http\Models\Administracion\Usuarios','fk_id_usuario_modificacion');
    }
    public function ramo(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\RamosSocioNegocio','fk_id_ramo');
    }
    public function tiposocio(){
        return $this->belongsToMany('App\Http\Models\SociosNegocio\TiposSocioNegocio','sng_det_tipo_socio_negocio','fk_id_socio_negocio','fk_id_tipo_socio');
    }
}