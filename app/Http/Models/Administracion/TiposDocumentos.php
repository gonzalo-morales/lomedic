<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class TiposDocumentos extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_tipo_documento';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_documento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre_documento'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

}