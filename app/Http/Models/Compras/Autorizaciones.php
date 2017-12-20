<?php

namespace App\Http\Models\Compras;

// use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\ModelCompany;
use DB;

class AutorizacionOrdenes extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_det_autorizaciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_autorizacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_tipo_documento','fk_id_condicion','fk_id_usuario_autoriza','fk_id_estatus','fecha_creacion','fecha_autorizacion','observaciones','activo','eliminar'];

    public $niceNames =[
        // 'nombre'=>'nombre',
        // 'campo'=>'campo',
        // 'rango_de'=>'rango de',
        // 'rango_hasta'=>'rango hasta'
    ];

    protected $dataColumns = [
        'campo'
    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    // protected $fields = [
    // ];

    // protected $eagerLoaders = ['condicionAutorizacion'];

    // function getNombreCompletoAttribute() {
    //     return $this->empleado->nombre.' '.$this->empleado->apellido_paterno.' '.$this->empleado->apellido_materno;
    // }

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_orden_compra'        => 'required',
        'fk_id_autorizacion'        => 'required',
        'fk_id_usuario_autoriza'    => 'required',
        'estatus'                   => 'required'
    ];


}
