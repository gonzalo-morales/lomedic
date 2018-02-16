<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use App\Http\Models\Servicios\Recetas;

class Afiliaciones extends ModelBase
{

    protected $table = 'maestro.gen_cat_afiliados';

    protected $primaryKey = 'id_afiliado';

//    public $incrementing = false;

    protected $fillable = [
        'id_afiliacion',
        'id_dependiente',
        'paterno',
        'materno',
        'nombre',
        'sexo',
        'edad',
        'genero',
        'edad_tiempo',
        'fecha_nacimiento',
        'fk_id_parentesco',
    ];

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $unique = ['id_afiliacion'];

    public $rules = [];

    protected $fields = [
        'id_afiliado' => '#',
        'id_afiliacion' => 'Numero de paciente',
        'FullName' => 'Nombre',
        'genero'=> 'Genero',
        'parentesco.nombre' => 'Parentesco'
    ];

    public function getFullNameAttribute()
    {
        return $this->paterno.' '.$this->materno.' '.$this->nombre;
    }

    public function recetas()
    {
        return $this->hasMany(Recetas::class,'fk_id_afiliacion','id_afiliacion');
    }
    public function parentesco()
    {
        return $this->hasOne(Parentescos::class,'id_parentesco','fk_id_parentesco');
    }
//    public function getCodigoPacieteAttribute()
//    {
//        return $this->selectRAW("id_afiliacion as codigo")->first();
//    }

}
