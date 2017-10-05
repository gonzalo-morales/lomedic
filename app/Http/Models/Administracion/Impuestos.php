<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelCompany;

class Impuestos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_impuestos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_impuesto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['impuesto'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function getFields()
    {
        return $this->fields;
    }

}
