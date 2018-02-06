<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use DB;

class EstatusCfdi extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_estatus_cfdi';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus_cfdi';
    
    protected $fillable = ['estatus','activo'];
    
    protected $fields = ['id_estatus_cfdi'=>'#','estatus'=>'Nombre de Estatus','activo_span' => 'Estatus'];
    
    public $rules = ['estatus'=>'required'];
}