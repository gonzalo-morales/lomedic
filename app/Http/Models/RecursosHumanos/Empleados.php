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
        'rfc','fecha_nacimiento','correo_personal','telefono','celular','fk_id_empresa_alta_imms',
        'numero_imms','fk_id_empresa_laboral','numero_infonavit','factor_documento'];

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
        'nombre' => 'required,alpha',
        'apellido_paterno' => 'required,alpha',
        'curp' => 'required,alpha_num',
        'rfc' => 'required,alpha_nu,',
        'fecha_nacimiento' => 'required',
        'numero_imms' => 'required,numeric',
    ];

    public function usuario()
    {
        $this->hasOne('app\Http\Models\Administracion\Usuarios');
    }

    public function empresa()
    {
        $this->$this->hasOne('app\Http\Models\Administracion\Empresas');
    }
}
