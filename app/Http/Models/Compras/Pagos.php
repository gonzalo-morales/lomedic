<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\Bancos;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\ModelCompany;
use DB;

class Pagos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fac_opr_pagos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'numero_referencia',
        'fk_id_banco',
        'fecha_pago',
        'monto',
        'fk_id_forma_pago',
        'fk_id_moneda',
        'tipo_cambio',
        'observaciones',
        'activo',
        'eliminar',
        'comprobante'
    ];

    public $niceNames =[
        'numero_referencia'=>'número de referencia',
        'fk_id_banco'=>'banco',
        'fecha_pago'=>'fecha pago',
        'fk_id_forma_pago'=>'forma pago',
        'fk_id_moneda'=>'moneda',
        'tipo_cambio'=>'tipo cambio'
    ];

//    protected $dataColumns = [
//        'fk_id_estatus_factura'
//    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_pago' => 'Número de pago',
        'fecha_pago' => 'Fecha de pago',
        'monto' => 'Monto',
        'forma_pago.forma_pago' => 'Forma pago',
        'moneda.moneda'=>'Moneda'
    ];

//    protected $eagerLoaders = ['proveedor','sucursal'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fecha_pago'=>'required',
        'monto'=>'required||regex:/^(\d{0,7}(\.\d{0,2})?)$/',
        'fk_id_forma_pago'=>'required',
        'fk_id_moneda'=>'required',
        'tipo_cambio'=>'required||regex:/^(\d{0,4}(\.\d{0,6})?)$/',
    ];

    public function moneda()
    {
        return $this->hasOne('App\Http\Models\Administracion\Monedas','id_moneda','fk_id_moneda');
    }

    public function forma_pago()
    {
        return $this->hasOne(FormasPago::class,'id_forma_pago','fk_id_forma_pago');
    }

    public function banco()
    {
        return $this->hasOne(Bancos::class,'id_banco','fk_id_banco');
    }
}
