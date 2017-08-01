<?php

namespace App\Http\Models\RecursosHumanos;

use Illuminate\Database\Eloquent\Model;

class Empleados extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rh_cat_empleados';

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
    protected $fillable = ['nombre', 'apellido_paterno', 'apellido_materno', 'curp',
        'rfc','fecha_nacimiento','correo_personal','telefono','celular','fk_id_empresa_alta_imss',
        'numero_imss','fk_id_empresa_laboral','numero_infonavit','factor_documento'];

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

        'nombre' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
        'apellido_paterno' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
        'apellido_materno' => 'regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
        'curp' => 'required|alpha_num',
        'rfc' => 'required|alpha_num',
        'fecha_nacimiento' => 'required',
        'correo_personal' => 'email',
        'numero_imss' => 'numeric',
    ];

    public function usuario()
    {
        $this->hasOne('app\Http\Models\Administracion\Usuarios');
    }

    public function empresa()
    {
        $this->$this->hasOne('app\Http\Models\Administracion\Empresas');
    }

    public function solicitudes()
    {
        $this->hasMany('app\Http\Models\Soporte\Solicitudes');
    }
}
