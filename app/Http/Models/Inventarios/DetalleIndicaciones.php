<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\ModelCompany;

class DetalleIndicaciones extends ModelCompany
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inv_det_upc_indicaciones_terapeuticas';

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
	protected $fillable = [
		'fk_id_indicacion_terapeutica'
	];

	/**
	 * Obtenemos upc relacionadas a detalle
	 * @return @belongsTo
	 */
	public function upc()
	{
		return $this->belongsTo(Upcs::class, 'fk_id_upc');
	}
}
