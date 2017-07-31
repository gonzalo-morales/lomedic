<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class AplicacionesMedicamentos extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_aplicaciones_medicamentos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_aplicacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['aplicacion', 'activo'];

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
        'aplicacion' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
    ];

}
