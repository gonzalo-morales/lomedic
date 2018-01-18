<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TipoInventario;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\ModelCompany;

class Inventarios extends ModelCompany
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inv_inventario';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_inventario';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['fk_tipo_inventario', 'fk_id_sucursal', 'fk_id_almacen', 'fecha_creacion', 'tipo_captura'];

	/*
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'tipo.tipo' => 'Tipo de inventario',
		'sucursal.sucursal' => 'Sucursal',
		'almacen.almacen' => 'Almacén',
		'fecha_creacion' => 'Fecha de creación',
		'tipo_captura_text' => 'Tipo de captura'
	];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'fk_tipo_inventario' => 'required',
		'fk_id_sucursal' => 'required',
		'fk_id_almacen' => 'required',
	];

	/**
	 * Nice names to validator
	 * @var array
	 */
	public $niceNames = [
		'fk_tipo_inventario' => 'Tipo de inventario',
		'fk_id_sucursal' => 'Sucursal',
		'fk_id_almacen' => 'Almacén',
	];

	public function getTipoCapturaTextAttribute() {
		return $this->tipo_captura == 2 ? 'HandHeld' : 'Manual';
	}

	/**
	 * Obtenemos detalle relacionadas a inventario
	 * @return @hasMany
	 */
	public function detalle()
	{
		return $this->hasMany(InventariosDetalle::class, 'fk_id_inventario', 'id_inventario');
	}

	/**
	 * Obtenemos sucursal relacionadas a inventario
	 * @return @belongsTo
	 */
	public function sucursal()
	{
		return $this->belongsTo(Sucursales::class, 'fk_id_sucursal', 'id_sucursal');
	}

	/**
	 * Obtenemos almacen relacionadas a inventario
	 * @return @belongsTo
	 */
	public function almacen()
	{
		return $this->belongsTo(Almacenes::class, 'fk_id_almacen', 'id_almacen');
	}

	/**
	 * Obtenemos tipo de inventario
	 * @return @belongsTo
	 */
	public function tipo()
	{
		return $this->belongsTo(TipoInventario::class, 'fk_tipo_inventario', 'id_tipo');
	}

}
