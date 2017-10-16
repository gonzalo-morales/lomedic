<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelCompany;
use DB;

class TiposProductosProyectos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pry_cat_tipos_productos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_producto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_proyecto','descripcion','activo'];

    public $niceNames =[
        'fk_id_proyecto'=>'proyecto',
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_proyecto' => 'required',
        'descripcion' => 'required'
    ];

    public $fields = [
        'id_tipo_producto'=>'ID',
        'proyecto.proyecto' => 'Proyecto',
        'descripcion' => 'Descripción',
        'activo_span' => 'Estatus'
    ];

    function proyecto()
    {
        return $this->hasOne(Proyectos::class,'id_proyecto','fk_id_proyecto');
    }
}
