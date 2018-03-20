<?php
namespace App\Http\Models\Compras;

use App\Http\Models\ModelBase;

class EstatusAutorizaciones extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_cat_estatus_autorizaciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus';
}