<?php
namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use App\Http\Models\Servicios\Recetas;

class Programas extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'gen_cat_programa';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_programa';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['nombre_programa','activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'nombre_programa' => 'required|max:255',
    ];

    protected $unique = ['nombre_programa'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = ['nombre_programa'=> 'Nombre Programa', 'activo_span' => 'Estatus'];

    public function recetas()
    {
        return $this->hasMany(Recetas::class,'fk_id_programa','id_programa');
    }
}