<?php

namespace App\Http\Models\Finanzas;

use App\Http\Models\ModelBase;

class CondicionesPago extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fnz_cat_condiciones_pago';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_condicion_pago';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['condicion_pago','activo'];
    
    protected $fields = [
        'condicion_pago' => 'Condicion Pago',
        'activo_span' => 'Estatus'
    ];

    protected $unique = ['condicion_pago'];
}
