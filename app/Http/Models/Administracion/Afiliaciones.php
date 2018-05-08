<?php
namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Servicios\Recetas;

class Afiliaciones extends ModelBase
{
    protected $table = 'gen_cat_afiliados';

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
        'fk_id_cliente',
        'activo',
    ];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
        'id_afiliacion' => ['required','max:12'],
        'nombre' => ['required','max:35','regex:/^[a-zA-Z\s]+/'],
        'paterno' => ['required','max:30','regex:/^[a-zA-Z\s]+/'],
        'materno' => ['required','max:30','regex:/^[a-zA-Z\s]+/'],
        'fk_id_parentesco' => ['required'],
		'id_dependiente' => ['max:32','numeric']
	];

    // protected $unique = ['id_afiliacion'];
   /**
     * The attributes that are mass assignable.
     * @var array
     */

    protected $fields = [
        'id_afiliado' => '#',
        'id_afiliacion' => 'Numero de paciente',
        'FullName' => 'Nombre',
        'genero'=> 'Genero',
        'parentesco.nombre' => 'Parentesco',
        'activo_span' => 'Estatus',
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
 
    public function socio_cliente()
    {
        return $this->hasOne(SociosNegocio::class, 'fk_id_cliente', 'id_afiliacion');
    }
}