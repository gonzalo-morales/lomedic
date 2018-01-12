<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\ModelCompany;

class SolicitudesDetalleSurtido extends ModelCompany
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'inv_handheld_surtido_pedidos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_surtido';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'fk_id_detalle_solicitud',
		'fk_id_sku',
		'fk_id_upc',
		'cantidad_solicitada_salida',
		'cantidad_escaneada',
		'falta_surtir',
		'fk_id_pedido'
	];


	/*
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	// protected $fields = [
	// ];

	/**
	 * Atributos de carga optimizada
	 * @var array
	 */
	// protected $eagerLoaders = [];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		// 'fk_tipo_inventario' => 'required',
		// 'fk_id_sucursal' => 'required',
		// 'fk_id_almacen' => 'required',
	];

	/**
	 * Nice names to validator
	 * @var array
	 */
	// public $niceNames = [
	// ];


	/**
	 * Obtenemos upc relacionadas a detalle
	 * @return @belongsTo
	 */
	public function upc()
	{
		return $this->belongsTo(Upcs::class, 'codigo_barras', 'upc');
	}

    public function sku()
    {
        return $this->belongsTo(Productos::class, 'fk_id_sku', 'id_sku');
    }
    
    public function pedidos()
    {
        return $this->hasOne(Pedidos::class,'id_pedido','fk_id_pedido');
    }
}
