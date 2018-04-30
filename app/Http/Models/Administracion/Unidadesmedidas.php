<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use App\Http\Models\Administracion\Presentaciones;

class UnidadesMedidas extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_unidades_medidas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_unidad_medida';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre', 'activo', 'clave'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [

        'nombre' => 'required|max:150|regex:/^[a-zA-Z\s]+/',
        'clave' => 'max:5'
    ];

    protected $unique = ['nombre','clave'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'nombre' => 'Unidad',
        'clave' => 'Clave',
        'activo_span' => 'Estatus'
    ];

    public function usuario()
    {
        $this->hasOne('app\Http\Models\Administracion\Usuarios');
    }

    public function empresa()
    {
        $this->$this->hasOne('app\Http\Models\Administracion\Empresas');
    }
	public function presentacion()
    {
        return $this->hasOne(Presentaciones::class,'fk_id_unidad_medida','id_unidad_medida');
    }
}
