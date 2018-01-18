<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\Empresas;
use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;

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

    protected $dataColumns = [
        'fk_id_estatus_oferta'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_oferta' => 'Número oferta',
        'proveedor.nombre_comercial' => 'Proveedor',
        'sucursal.sucursal' => 'Sucursal entrega',
        'vigencia' => 'Vigencia',
        'estatus.estatus' => 'Estatus'
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_sucursal'=>'required',
        'fk_id_proveedor'=>'required',
        'fk_id_moneda'=>'required',
        'descuento_oferta'=>'nullable||regex:/^(\d{0,2}(\.\d{0,4})?\)$/'
    ];

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class,'fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_oferta');
    }

    public function empresa()
    {
        return $this->belongsTo(Empresas::class,'fk_id_empresa','id_empresa');
    }

    public function proveedor()
    {
        return $this->belongsTo(SociosNegocio::class,'fk_id_proveedor');
    }

    public function moneda()
    {
        return $this->hasOne(Monedas::class,'id_moneda','fk_id_moneda');
    }

    public function DetalleOfertas()
    {
        return $this->belongsTo(DetalleOfertas::class,'fk_id_documento');
    }
}
