<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use DB;

class EstatusDocumentos extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_estatus_documentos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus';
}
