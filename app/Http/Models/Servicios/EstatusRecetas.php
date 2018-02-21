<?php

namespace App\Http\Models\Servicios;

use App\Http\Models\ModelBase;

class EstatusRecetas extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.rec_cat_estatus_recetas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus_receta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estatus_receta'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [];

}
