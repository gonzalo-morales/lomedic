<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 15/11/2017
 * Time: 16:32
 */

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposDocumentos extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_tipo_documento';

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

    /**
     * The validation rules
     * @var array
     */
    public $rules = ['nombre_documento' => 'required'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'nombre_documento' => 'Tipo de documento',
    ];

}
