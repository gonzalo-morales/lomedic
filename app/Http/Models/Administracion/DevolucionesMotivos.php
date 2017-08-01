<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class DevolucionesMotivos extends Model
{
    protected $table = 'gen_cat_devoluciones_motivos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_devolucion_motivo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['devolucion_motivo', 'solicitante_devolucion','activo'];//Solicitante devolución. false: localidad; true: proveedor;

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
        'devolucion_motivo' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
    ];
}
