<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelBase;

class Ubicaciones extends ModelBase
{
    protected $table = 'inv_cat_ubicaciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_ubicacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_almacen','rack', 'ubicacion', 'posicion', 'nivel', 'activo','eliminar'];

}