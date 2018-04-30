<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\ModelCompany;
use DB;

class Solicitudes extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_opr_solicitudes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_documento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_sucursal',
        'fk_id_departamento',
        'fecha_creacion',
        'fecha_necesidad',
        'fecha_cancelacion',
        'motivo_cancelacion',
        'fk_id_estatus_solicitud',
        'fk_id_solicitante',
        'total_solicitud',
        'total_impuesto',
        'total_subtotal',
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_documento' => 'Número Solicitud',
        'nombre_completo'  => 'Solicitante',
        'nombre_sucursal' => 'Sucursal',
        'fecha_creacion' => 'Fecha de solicitud',
        'fecha_necesidad' => 'Fecha necesidad',
        'estatus_documento_span' => 'Estatus',
    ];

    protected $dataColumns = [
        'fk_id_estatus_solicitud'
    ];

    function getNombreCompletoAttribute() {
        return $this->usuario->usuario.' / '.$this->empleado['nombre'].' '.$this->empleado['apellido_paterno'].' '.$this->empleado['apellido_materno'];
        // return $this->empleado->nombre.' '.$this->empleado->apellido_paterno.' '.$this->empleado->apellido_materno;
    }

    function getNombreSucursalAttribute(){
        return $this->sucursales->sucursal;
    }

    function getEstatusSolicitudAttribute(){
        return $this->estatus->estatus;
    }

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_solicitante' => 'required',
        'fk_id_sucursal' => 'required',
        'fecha_necesidad' => 'required'
    ];

    public function getFields()
    {
        return $this->fields;
    }

    public function usuario()
    {
        return $this->belongsTo(Usuarios::class,'fk_id_solicitante','id_usuario');
    }
    public function empleado()
    {
        return $this->belongsTo(Empleados::class,'fk_id_solicitante','id_empleado');
    }
    public function sucursales()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_solicitud');
    }

    public function detalle()
    {
        return $this->hasMany('App\Http\Models\Compras\DetalleSolicitudes','fk_id_documento', 'id_documento');
    }

    public function getSolicitanteFormatedAttribute() {
        return $this->empleado->nombre." ".$this->empleado->apellido_paterno." ".$this->empleado->apellido_materno;
    }
}