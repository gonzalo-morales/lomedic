<?php

namespace App\Http\Models\Compras;

// use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\ModelCompany;
use App\Http\Models\Compras\CondicionesAutorizacion;
use DB;

class EstatusAutorizacion extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.com_cat_estatus_autorizaciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['estatus','activo','eliminar'];

    public $niceNames =[];

    protected $dataColumns = [
        'estatus'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    // protected $fields = [
    // ];

    protected $eagerLoaders = ['estatus'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'estatus'   => 'required'
    ];

}
