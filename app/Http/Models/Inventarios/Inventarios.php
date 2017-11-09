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
	protected $fillable = ['fk_tipo_inventario', 'fk_id_sucursal', 'fk_id_almacen', 'fecha_creacion'];

	/*
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'tipo.tipo' => 'Tipo de inventario',
		'sucursal.sucursal' => 'Sucursal',
		'almacen.almacen' => 'Almacén',
	];

	/**
	 * Atributos de carga optimizada
	 * @var array
	 */
	protected $eagerLoaders = ['tipo', 'sucursal', 'almacen'];

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

	public function tipo()
	{
		return $this->belongsTo(TipoInventario::class, 'fk_tipo_inventario', 'id_tipo');
	}

}
