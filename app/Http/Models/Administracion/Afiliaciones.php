<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use App\Http\Models\Servicios\Recetas;

class Afiliaciones extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_afiliados';

    /**
     * The primary key of the table
     * @var string
     */
//    protected $primaryKey = 'id_afiliacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_afiliacion','id_dependiente', 'paterno','materno','nombre','sexo','edad','genero','edad_tiempo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'nombre' => 'Nombre'
    ];

    public function getFullNameAttribute()
    {
        return $this->paterno.' '.$this->materno.' '.$this->nombre;
    }

    public function recetas()
    {
        return $this->hasMany(Recetas::class,'fk_id_afiliacion','id_afiliacion');
    }
}
