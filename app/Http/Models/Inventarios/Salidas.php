<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Inventarios\SalidasDetalle;
use App\Http\Models\ModelCompany;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\DireccionesSociosNegocio;
use App\Http\Models\SociosNegocio\SociosNegocio;

class Salidas extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_salidas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_salida';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_tipo_salida', 'fk_id_socio_negocio', 'fk_id_proyecto', 'entrega_por', 'nombre_conductor', 'placas_vehiculo', 'paqueteria', 'fk_tipo_entrega', 'fk_id_direccion_entrega', 'fecha_entrega', 'fecha_salida'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'cliente.nombre_comercial' => 'Cliente',
        'proyecto.proyecto' => 'Proyecto',
        'sucursal_entrega.direccion_concat' => 'Sucursal de entrega',
        'fecha_entrega' => 'Fecha de entrega',
        'estatus_text' => 'Estatus',
    ];

    /**
     * Atributos de carga optimizada
     * @var array
     */
    protected $eagerLoaders = ['cliente', 'proyecto', 'sucursal_entrega'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_tipo_salida' => 'required',
        'fk_id_socio_negocio' => 'required',
        'fk_id_proyecto' => 'required',
        'entrega_por' => 'required',
        'fk_tipo_entrega' => 'required',
        'fk_id_direccion_entrega' => 'required',
        'fecha_entrega' => 'required'
    ];

    /**
     * Nice names to validator
     * @var array
     */
    public $niceNames = [
        'fk_tipo_salida' => 'Tipo de salida',
        'fk_id_socio_negocio' => 'Cliente',
        'fk_id_proyecto' => 'Proyecto',
        'entrega_por' => 'Entrega por',
        'fk_tipo_entrega' => 'Tipo de entrega',
        'fk_id_direccion_entrega' => 'Sucursal de entrega',
        'fecha_entrega' => 'Fecha de entrega'
    ];

    /**
     * Accessor
     * @return string
     */
    public function getEstatusTextAttribute() {
        switch ($this->estatus) {
            case 0:
                $estatus = 'Abierto';
                break;

            case 1:
                $estatus = 'Cerrado';
                break;

            case 2:
                $estatus = 'Cancelado';
                break;

            default:
                $estatus = 'Abierto';
                break;
        }
        return $estatus;
    }

    /**
     * Obtenemos cliente relacionado
     * @return @belongsTo
     */
    public function cliente()
    {
        return $this->belongsTo(SociosNegocio::class, 'fk_id_socio_negocio', 'id_socio_negocio');
    }

    /**
     * Obtenemos proyecto relacionado
     * @return @belongsTo
     */
    public function proyecto()
    {
        return $this->belongsTo(Proyectos::class, 'fk_id_proyecto', 'id_proyecto');
    }

    /**
     * Obtenemos sucursal_entrega relacionado
     * @return @belongsTo
     */
    public function sucursal_entrega()
    {
        return $this->belongsTo(DireccionesSociosNegocio::class, 'fk_id_direccion_entrega', 'id_direccion');
    }

    /**
     * Obtenemos detalle relacionadas a solicitud
     * @return @hasMany
     */
    public function detalle()
    {
        return $this->hasMany(SalidasDetalle::class, 'fk_id_salida', 'id_salida');
    }
}
