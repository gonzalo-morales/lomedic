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
    protected $fillable = ['cedula','paterno', 'matenro','nombre','rfc','consultorio'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'cedula' => 'Fecha Receta',
        'paterno' => 'Apellido Paterno',
        'materno' => 'Apellido Matenro',
        'nombre' => 'Nombre',
        'rfc' => 'RFC'
    ];

    public function getNombreCompletoAttribute(){
        return $this->paterno.' '.$this->materno.' '.$this->nombre;
    }

    public function recetas()
    {
        return $this->hasMany(Recetas::class,'fk_id_medico','id_medico');
    }
}
