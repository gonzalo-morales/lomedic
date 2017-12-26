<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class RegimenesFiscales extends ModelBase
{
    protected $table = 'sat_cat_regimenes_fiscales';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_regimen_fiscal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['regimen_fiscal','activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'regimen_fiscal' => 'required|max:255'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'regimen_fiscal' => 'Régimen fiscal',
        'activo_span' => 'Estatus'
    ];

    public $niceNames = [
        'regimen_fiscal' => 'Régimen fiscal',
    ];
}