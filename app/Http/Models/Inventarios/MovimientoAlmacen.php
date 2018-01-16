<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Inventarios\MovimientoAlmacenDetalle;
use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\Inventarios\Stock;

class MovimientoAlmacen extends ModelCompany
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inv_opr_movimiento_almacen';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_movimiento';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'fk_id_usuario',
		'fk_id_sucursal',
		'fk_id_almacen',
		'total_productos',
		'fecha_operacion'
	];

	/*
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'usuario.nombre_corto' => 'Usuario',
		'sucursal.sucursal' => 'Sucursal',
		'almacen.almacen' => 'Almacen',
		'fecha_operacion' => 'Fecha que se registró el cambio',
		'total_productos' => 'Cantidad de productos en que se realizó cambio'
	];

	/**
	 * Atributos de carga optimizada
	 * @var array
	 */
	protected $eagerLoaders = ['sucursal', 'usuario','almacen'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'fk_id_usuario' => 'required',
		'fk_id_sucursal' => 'required',
		'fk_id_almacen' => 'required'
	];

	/**
	 * Nice names to validator
	 * @var array
	 */
	public $niceNames = [
		'fk_id_sucursal' => 'Sucursal',
		'fk_id_usuario' => 'Usuario',
		'fk_id_almacen' => 'Almacen'
	];

	/**
	 * Obtenemos detalle relacionadas a inventario
	 * @return @hasMany
	 */
	public function detalle()
	{
		return $this->hasMany(MovimientoAlmacenDetalle::class, 'fk_id_movimiento', 'id_movimiento');
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
	 * Obtenemos usuario relacionadas a inventario
	 * @return @belongsTo
	 */
	public function usuario()
	{
		return $this->belongsTo(Usuarios::class, 'fk_id_usuario', 'id_usuario');
	}

	public function almacen()
	{
		return $this->belongsTo(Almacenes::class, 'fk_id_almacen', 'id_almacen');
	}

	public function stock(){

		return $this->hasMany(Stock::class, 'fk_id_stock', 'id_stock');
	}
}
