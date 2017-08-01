<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Monedas extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sat_cat_monedas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_moneda';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['moneda', 'descripcion','total_decimales','porcentaje_variacion','activo'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function numeroscuenta()
    {
        return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
    }

}
