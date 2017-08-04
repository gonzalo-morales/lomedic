<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class VehiculosMarcas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_vehiculos_marcas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_marca';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['marca', 'activo'];

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
        'marca' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
    ];

    public function modelos()
    {
        return $this->hasMany('App\Http\Models\Administracion\VehiculosModelos');
    }


    // public function vehiculos(){
    //     return $this->hasMany('App\Http\Models\Servicios\Vehiculos','id_marca');
    // }

}
