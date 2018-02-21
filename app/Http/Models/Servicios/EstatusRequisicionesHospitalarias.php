<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 27/12/2017
 * Time: 12:31
 */


namespace App\Http\Models\Servicios;

use App\Http\Models\ModelBase;

class EstatusRequisicionesHospitalarias extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'req_cat_estatus_requisicion_hospitalaria';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus_requisicion_hospitalaria';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_estatus_requisicion_hospitalaria',
        'estatus_requisicion_hospitalaria'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [];
}
