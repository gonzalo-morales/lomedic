<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Inventarios\Ubicaciones;
use App\Http\Models\Administracion\Localidades;
use App\Http\Models\ModelCompany;

class Stock extends ModelCompany
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inv_opr_stock';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_stock';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'fk_id_localidad',
		'fk_id_sucursal',
		'fk_id_almacen',
		'fk_id_ubicacion'
	];

	/*
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'fk_id_localidad' => 'Localidad',
		'fk_id_sucursal' => 'Sucursal',
		'fk_id_almacen' => 'Almacén',
		'fk_id_ubicacion' => 'Ubicación'
	];

	/**
	 * Atributos de carga optimizada
	 * @var array
	 */
	protected $eagerLoaders = ['almacen','sucursal','ubicacion','localidad'];

	/**
	 * Obtenemos detalle relacionadas a inventario
	 * @return @hasMany
	 */
	public function localidad()
	{
		return $this->belongsTo(Localidades::class, 'fk_id_localidad','id_localidad');
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
	 * Obtenemos detalle relacionadas a inventario
	 * @return @hasMany
	 */
	public function ubicacion()
	{
		return $this->belongsTo(Ubicaciones::class, 'fk_id_ubicacion','id_ubicacion');
	}

    /**
     * Obtenemos ubicacion relacionada
     * @return @belongsTo
     */
    public function dummy()
    {
        return $this->belongsTo(Ubicaciones::class, 'fk_id_ubicacion', 'id_ubicacion');
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
	 * Obtenemos información del inventario en el detalle
	 * @return @belongsTo
	 */
	public function detalle()
	{
		return $this->hasMany(StockDetalle::class, 'fk_id_stock','id_stock');
	}
}
