<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 11/09/2017
 * Time: 13:15
 */


namespace App\Http\Models\Servicios;

use App\Http\Models\ModelBase;

class EstatusRequisiciones extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rho_cat_estatus_requisicion';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus_requisicion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_estatus_requisicion','estatus'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'id_estatus' => 'required',
        'estatus' => 'required',
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [];
}
