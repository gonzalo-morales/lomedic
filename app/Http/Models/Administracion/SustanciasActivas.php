<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class SustanciasActivas extends Model
{
    protected $table = 'gen_cat_sustancias_activas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_sustancia_activa';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sustancia_activa', 'opcion_gramaje','activo'];

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
        'sustancia_activa' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
    ];
}
