<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class VehiculosModelos extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_vehiculos_modelos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_modelo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['modelo', 'fk_id_marca','activo'];

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
        'modelo' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
        'fk_id_marca' => 'required|numeric'
    ];

    public function marcas()
    {
        return $this->belongsTo('app\Http\Models\Administracion\VehiculosMarcas');
    }

    public function vehiculos(){
        return $this->belongsTo('App\Http\Models\Servicios\Vehiculos','id_modelo');
    }
}
