<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Unidadesmedidas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_unidades_medidas';

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

        'nombre' => 'required',
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
