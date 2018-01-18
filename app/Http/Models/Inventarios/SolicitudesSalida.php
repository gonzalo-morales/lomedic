<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Inventarios\SolicitudesSalidaDetalle;
use App\Http\Models\ModelCompany;
use App\Http\Models\Proyectos\Proyectos;
use App\Http\Models\SociosNegocio\DireccionesSociosNegocio;
use App\Http\Models\SociosNegocio\SociosNegocio;

class SolicitudesSalida extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_solicitudes_salida';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_solicitud';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_socio_negocio', 'fk_id_proyecto', 'fk_id_direccion_entrega', 'fecha_entrega', 'fecha_solicitud'];

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
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_socio_negocio' => 'required',
        'fk_id_proyecto' => 'required',
        'fk_id_direccion_entrega' => 'required',
        'fecha_entrega' => 'required',
    ];

    /**
     * Nice names to validator
     * @var array
     */
    public $niceNames = [
        'fk_id_socio_negocio' => 'Cliente',
        'fk_id_proyecto' => 'Proyecto',
        'fk_id_direccion_entrega' => 'Sucursal de entrega',
        'fecha_entrega' => 'Fecha de entrega',
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
        return $this->hasMany(SolicitudesSalidaDetalle::class, 'fk_id_solicitud', 'id_solicitud');
    }


}
