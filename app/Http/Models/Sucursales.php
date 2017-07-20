<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;

class Sucursales extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ges_cat_sucursales';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_sucursal';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre_sucursal', 'fk_id_localidad', 'latitud', 'longitud',
        'fk_id_tipo_sucursal','fk_id_red','fk_id_supervisor','fk_id_cliente','embarque','calle',
        'no_interior','no_exterior','colonia','codigo_postal','fk_id_municipio','fk_id_estado',
        'fk_id_pais','registro_sanitario','tipo_batallon','region','zona_militar','telefono1',
        'telefono2','clave_presupuestal','fk_id_jurisdiccion','activo'];

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
		'nombre_sucursal' => 'required|alpha_num',
	];

}
