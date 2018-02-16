<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use App\Http\Models\Servicios\Recetas;

class Medicos extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_medicos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_medico';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cedula','paterno', 'materno','nombre','rfc','activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'cedula' => 'max:12|required',
        'paterno' => 'max:30|required|regex:/^[a-zA-Z\s]+/',
        'materno' => 'max:30|required|regex:/^[a-zA-Z\s]+/',
        'nombre' => 'max:35|required|regex:/^[a-zA-Z\s]+/',
        'rfc' => 'max:16',
    ];

    protected $unique = ['cedula','rfc'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'cedula' => 'Cedula',
        'paterno' => 'Apellido Paterno',
        'materno' => 'Apellido Matenro',
        'nombre' => 'Nombre',
        'rfc' => 'Rfc',
        'activo_span' => 'Estatus'
    ];

    public function getNombreCompletoAttribute(){
        return $this->paterno.' '.$this->materno.' '.$this->nombre;
    }

    public function recetas()
    {
        return $this->hasMany(Recetas::class,'fk_id_medico','id_medico');
    }
}
