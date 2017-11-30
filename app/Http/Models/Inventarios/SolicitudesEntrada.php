<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;

class SolicitudesEntrada extends ModelCompany
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
    protected $fillable = [];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        // 'cliente.nombre_comercial' => 'Cliente',
        // 'proyecto.proyecto' => 'Proyecto',
        // 'sucursal_entrega.direccion_concat' => 'Sucursal de entrega',
        // 'fecha_entrega' => 'Fecha de entrega',
        // 'estatus_text' => 'Estatus',
        'dummy' => 'Dummy'
    ];

    /**
     * Atributos de carga optimizada
     * @var array
     */
    protected $eagerLoaders = [];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        // 'fk_id_socio_negocio' => 'required',
        // 'fk_id_proyecto' => 'required',
        // 'fk_id_direccion_entrega' => 'required',
        // 'fecha_entrega' => 'required',
    ];

    /**
     * Nice names to validator
     * @var array
     */
    public $niceNames = [
        // 'fk_id_socio_negocio' => 'Cliente',
        // 'fk_id_proyecto' => 'Proyecto',
        // 'fk_id_direccion_entrega' => 'Sucursal de entrega',
        // 'fecha_entrega' => 'Fecha de entrega',
    ];

    public function getDummyAttribute() {
        return 'oks';
    }


}
