<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelCompany;
use App\Http\Models\SociosNegocio\SociosNegocio;
use DB;

class ProyectosProductosDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pry_det_proyectos_productos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_clave_cliente_proyecto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_cliente','fk_id_proyecto','activo'];

    public $niceNames =[
        'fk_id_cliente'=>'cliente',
        'fk_id_proyecto'=>'proyecto',
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_cliente' => 'required',
        'fk_id_proyecto' => 'required',
    ];

    public $fields = [
        'id_proyecto_producto' => 'Proyecto',
        'cliente.nombre_corto' => 'Cliente',
        'proyecto.propyecto' => 'Proyecto',
        'activo_span' => 'Estatus'
    ];

    function cliente()
    {
        return $this->belongsTo(SociosNegocio::class,'id_socio_negocio','fk_id_cliente');
    }

    function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'id_proyecto','fk_id_proyecto');
    }
}
