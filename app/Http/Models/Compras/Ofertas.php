<?php

namespace App\Http\Models\Compras;

use App\Http\Models\ModelCompany;
use DB;

class Ofertas extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_opr_ofertas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_oferta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_sucursal','fk_id_estatus_oferta','fk_id_proveedor','vigencia','fecha_creacion',
        'condiciones_oferta','tiempo_entrega','fk_id_moneda','descuento_oferta','fk_id_empresa'];

    public $niceNames =[
        'fk_id_sucursal'=>'sucursal',
        'fk_id_moneda'=>'moneda',
        'fk_id_proveedor'=>'proveedor'
    ];

    protected $dataColumns = [];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_oferta' => 'Número oferta',
        'proveedor.nombre_corto' => 'Proveedor',
        'sucursales.sucursal' => 'Sucursal entrega',
        'vigencia' => 'Vigencia'
    ];

    protected $eagerLoaders = ['proveedor','sucursal'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_sucursal'=>'required',
        'fk_id_proveedor'=>'required',
        'fk_id_moneda'=>'required',
        'descuento_oferta'=>'numeric|regex:/^\d{0,2}(\.\d{0,4})?$/'
    ];

    public function sucursal()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne('App\Http\Models\Compras\EstatusSolicitudes','id_estatus','fk_id_estatus_oferta');
    }

    public function empresa()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Empresas','fk_id_empresa','id_empresa');
    }

    public function proveedor()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_proveedor');
    }

    public function moneda()
    {
        return $this->hasOne('App\Http\Models\Administracion\Monedas','id_moneda','fk_id_moneda');
    }

    public function DetalleOfertas()
    {
        return $this->hasMany('App\Http\Models\Compras\DetalleOfertas','fk_id_oferta','id_oferta');
    }
}
