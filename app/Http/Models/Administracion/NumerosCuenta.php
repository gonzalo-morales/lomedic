<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class NumerosCuenta extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_numeros_cuenta';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_numero_cuenta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['numero_cuenta', 'fk_id_banco','fk_id_sat_moneda','fk_id_empresa','activo'];

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
        'numero_cuenta' => 'required|numeric',
        'fk_id_banco' => 'required|numeric',
        'fk_id_empresa' => 'required|numeric',
        'fk_id_sat_moneda' => 'required|numeric'
    ];

    public function bancos()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Bancos');
    }

    public function empresas()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Empresas');
    }

    public function monedas()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Monedas');
    }
}
