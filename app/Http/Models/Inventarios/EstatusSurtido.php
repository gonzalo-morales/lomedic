<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 3/1/2018
 * Time: 04:41
 */

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelBase;

class EstatusSurtido extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'req_cat_estatus_surtido';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus_surtido';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estatus_surtido'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [];

}
