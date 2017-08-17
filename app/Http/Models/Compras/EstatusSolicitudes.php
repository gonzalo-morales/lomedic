<?php

namespace App\Http\Models\Compras;

use App\Http\Models\ModelCompany;
use DB;
use Illuminate\Database\Eloquent\Model;

class EstatusSolicitudes extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.com_cat_estatus_solicitudes_compras';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


}
