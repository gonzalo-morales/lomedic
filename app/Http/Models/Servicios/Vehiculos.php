<?php

namespace App\Http\Models\Servicios;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vehiculos extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'serv_vehiculos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_vehiculo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_marca', 'modelo', 'fk_id_modelo','numero_serie','placa','capacidad_tanque','rendimiento','lineas_tanque','litros_linea',
        'no_tarjeta','fk_id_combustible','iave','folio','fk_id_sucursal','fk_id_usuario_captura','fk_id_usuario_modificacion',
        'activo','eliminar'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    const CREATED_AT = 'fecha_captura';
 	const UPDATED_AT = 'fecha_modificacion';
    // public $timestamps = false;

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'marca'             => 'required',
        'modelos'           => 'required',
        'modelo'            => 'required',
        'numeroSerie'       => 'required',
        'placa'             => 'required',
        'capacidad'         => 'required',
        'rendimiento'       => 'required',
        'lineasPorTanque'   => 'required',
        'litrosPorLinea'    => 'required',
        'numeroTarjeta'     => 'required',
        'combustible'       => 'required',
        'folioChecklist'    => 'required',
        'sucursal'          => 'required',
    ];


    /*Information for selects*/
    public function user()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Usuarios');
    }

    public function company()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Empresas');
    }

    public function marca(){
        return $this->belongsTo('App\Http\Models\Administracion\VehiculosMarcas', 'fk_id_marca');
    }

    public function modelos(){
        return $this->belongsTo('App\Http\Models\Administracion\VehiculosModelos', 'fk_id_modelo');
    }

    public function sucursales(){
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales', 'fk_id_sucursal');
    }

    public function combustibles(){
        return $this->belongsTo('App\Http\Models\Administracion\Tipocombustible', 'fk_id_combustible');
    }

}
