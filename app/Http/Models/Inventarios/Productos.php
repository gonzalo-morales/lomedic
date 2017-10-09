<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;

class Productos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.inv_cat_skus';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_sku';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku','descripcion'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_sku' => 'ID',
        'sku' => 'SKU',
        'descripcion' => 'Descripcion'
    ];

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
    public $rules = [
        'sku' => 'required',
        'descripcion' => 'required'
    ];

    public function upcs()
    {
        return $this->hasMany('App\Http\Models\Inventarios\Upcs','id_upc');
    }
}
