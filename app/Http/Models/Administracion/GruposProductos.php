<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class GruposProductos extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_grupo_productos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_grupo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['descripcion','descripcion_producto','nomenclatura','tipo','activo'];

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

        'descripcion' => 'required',
        'descripcion_producto' => 'required',
        'nomenclatura' => 'required',
        'tipo' => 'required',

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
