<?php

namespace App\Http\Models\Facturas;

use App\Http\Models\ModelBase;
use DB;

class EstatusFacturas extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.fact_cat_estatus_facturas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus_factura';
}
