<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\ModelCompany;

class InventariosDetalle extends ModelCompany
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inv_det_inventario';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_detalle';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['fk_id_inventario', 'codigo_barras', 'cantidad_toma', 'cantidad_sistema', 'no_lote', 'caducidad', 'fk_id_almacen', 'fk_id_ubicacion', 'estatus', 'observaciones'];

	/*
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Obtenemos upc relacionadas a detalle
	 * @return @belongsTo
	 */
	public function upc()
	{
		return $this->belongsTo(Upcs::class, 'codigo_barras', 'upc');
	}
}
