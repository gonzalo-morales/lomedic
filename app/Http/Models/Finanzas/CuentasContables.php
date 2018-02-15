<?php

namespace App\Http\Models\Finanzas;

use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\AgrupadoresCuentas;

class CuentasContables extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.fnz_cat_cuentas_contables';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_cuenta_contable';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cuenta','nombre','tipo','cuenta_mayor','cuenta_efectivo','afectable','fk_id_cuenta_padre','fk_id_agrupador_cuenta','activo'];
    
    protected $fields = [
        'padre.cuenta' => 'Subcuenta de',
        'cuenta' => 'Cuenta',
        'nombre' => 'Nombre',
        'agrupadorcuenta.codigo_agrupador' => 'Agrupador SAT',
        'activo_span' => 'Estatus'
    ];

    protected $unique = ['cuenta','nombre'];
    
    public function padre()
    {
        return $this->hasOne(CuentasContables::class,'id_cuenta_contable','fk_id_cuenta_padre');
    }
    
    public function agrupadorcuenta()
    {
        return $this->hasOne(AgrupadoresCuentas::class,'id_agrupador_cuenta','fk_id_agrupador_cuenta');
    }
}
