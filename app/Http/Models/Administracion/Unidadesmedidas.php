<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

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
    protected $fillable = ['nombre', 'activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [

        'nombre' => 'required|max:255|regex:/^[a-zA-Z\s]+/',
    ];

    protected $unique = ['nombre'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'nombre' => 'Descripcion',
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
}
