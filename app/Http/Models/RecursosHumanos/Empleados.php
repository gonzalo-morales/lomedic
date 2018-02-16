<?php

namespace App\Http\Models\RecursosHumanos;

use App\Http\Models\ModelBase;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\SociosNegocio\SociosNegocio;

class Empleados extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.rh_cat_empleados';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_empleado';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'nombre','apellido_paterno','apellido_materno','curp','rfc','fecha_nacimiento','correo_personal','telefono','celular','fk_id_empresa_alta_imss','numero_imss',
        'fk_id_empresa_laboral','numero_infonavit','factor_descuento','activo','fk_id_departamento','fk_id_puesto','fk_id_sucursal'
    ];
    
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'nombre' => 'Nombre',
        'apellido_paterno' => 'Apellido Paterno',
        'apellido_materno' => 'Apellido Materno',
        'departamento.descripcion' => 'Departamento',
        'puesto.descripcion' => 'Puesto',
        'fecha_nacimiento' => 'Fecha Nacimiento',
        'correo_personal' => 'Correo Personal',
        'telefono' => 'Telefono'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $unique = ['correo_personal','celular'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [

        'nombre' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
        'apellido_paterno' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
        'apellido_materno' => 'regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
        'curp' => 'required|alpha_num',
        'rfc' => 'required|alpha_num',
        'fecha_nacimiento' => 'required',
        'correo_personal' => 'email',
        'numero_imss' => 'numeric',
        'fk_id_departamento' => 'required',
        'fk_id_puesto' => 'required',
    ];

    public function usuario()
    {
        $this->hasOne('App\Http\Models\Administracion\Usuarios');
    }

    public function empresa()
    {
        $this->$this->hasOne('App\Http\Models\Administracion\Empresas');
    }

    public function solicitudes()
    {
        return $this->hasMany('App\Http\Models\Soporte\Solicitudes','fk_id_empleado_solicitud','id_empleado');
    }

    public function solicitudes_encargado()
    {
        return $this->hasMany('App\Http\Models\Soporte\Solicitudes','fk_id_empleado_tecnico','id_empleado');
    }

    public function sucursales()
    {
        return $this->belongsToMany(Sucursales::class,'maestro.ges_det_empleado_sucursal','fk_id_empleado','fk_id_sucursal');
    }

    public function departamento()
    {
        return $this->belongsTo(Departamentos::class,'fk_id_departamento');
    }

    public function puesto()
    {
        return $this->belongsTo(Puestos::class,'fk_id_puesto');
    }
    public function ejecutivocompra(){
        return $this->hasOne(SociosNegocio::class,'fk_id_ejecutivo_compra','id_empleado');
    }
}
