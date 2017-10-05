<?php

namespace App\Http\Models\Finanzas;

use App\Http\Models\ModelCompany;

class CondicionesPago extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.fnz_cat_condiciones_pago';

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
    protected $fillable = ['condicion_pago'];

}
