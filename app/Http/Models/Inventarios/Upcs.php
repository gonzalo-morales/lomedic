<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;

class Upcs extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.inv_cat_upcs';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_upc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['upc','descripcion','fk_id_sku'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_upc' => 'ID',
        'upc' => 'UPC',
        'descripcion' => 'Descripción',
        'fk_id_sku' => 'SKU'
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
        'upc' => 'required',
        'descripcion' => 'required',
        'fk_id_sku' => 'required'
    ];

    public function sku()
    {
        return $this->belongsToMany('App\Http\Models\Inventarios\Skus','maestro.inv_det_sku_upc','fk_id_sku','fk_id_sku');
    }
}
