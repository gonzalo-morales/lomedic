<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use DB;

class EstatusDocumentos extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_estatus_documentos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_estatus';
    
    protected $fillable = ['estatus','activo'];
    
    protected $fields = ['id_estatus'=>'#','estatus'=>'Nombre de Estatus','activo_span' => 'Estatus'];
    
    public $rules = ['estatus'=>'required|max:50|regex:/^[a-zA-Z\s]+/'];

    protected $unique = ['estatus'];
}