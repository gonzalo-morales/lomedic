<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Proveedores extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_proveedor';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_proveedor';

}