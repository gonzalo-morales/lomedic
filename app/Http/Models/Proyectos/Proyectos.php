<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;

class Proyectos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pry_cat_proyectos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_proyecto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['proyecto','activo','fk_id_cliente','fecha_contrato','fecha_inicio_contrato',
        'fecha_fin_contrato','numero_contrato','numero_proyecto','monto_adjudicado','fk_id_clasificacion_proyecto',
        'plazo','representante_legal','numero_fianza'];

    public $niceNames =[
        'fk_id_cliente'=>'cliente',
        'fecha_contrato' =>'fecha de contrato',
        'fecha_inicio_contrato' => 'fecha de inicio de contrato',
        'fecha_fin_contrato' => 'fecha de fin de contrato',
        'numero_contrato' => 'número de contrato',
        'numero_proyecto' => 'número de proyecto',
        'monto_adjudicado' => 'monto adjudicado',
        'fk_id_clasificacion_proyecto' => 'clasificación proyecto',
        'representante_legal' => 'representante legal',
        'numero_fianza' => 'número de fianza'
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
        'proyecto' => 'required',
        'fk_id_cliente' => 'required',
        'fecha_contrato' => 'required',
        'fk_id_clasificacion_proyecto' => 'required',
    ];

    public $fields = [
        'proyecto' => 'Proyecto',
        'cliente.nombre_corto' => 'Cliente',
        'numero_contrato' => 'No. Contrato',
        'fecha_inicio_contrato' => 'Inicio de contrato',
        'fecha_fin_contrato' => 'Fin de contrato',
        'activo_span' => 'Estatus'
    ];

    function cliente()
    {
        return $this->hasOne(SociosNegocio::class,'id_socio_negocio','fk_id_cliente');
    }

    function ProyectosProductos()
    {
        return $this->hasMany(ProyectosProductos::class,'fk_id_proyecto','id_proyecto');
    }
}
