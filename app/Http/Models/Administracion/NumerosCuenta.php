<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class NumerosCuenta extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_numeros_cuenta';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_numero_cuenta';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['numero_cuenta', 'fk_id_banco','fk_id_sat_moneda','fk_id_empresa','activo'];

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
		'numero_cuenta' => 'required|numeric',
		'fk_id_banco' => 'required|numeric',
		'fk_id_empresa' => 'required|numeric',
		'fk_id_sat_moneda' => 'required|numeric'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'numero_cuenta' => 'Número de cuenta',
		'banco.razon_social' => 'Banco',
		'moneda.descripcion' => 'Moneda',
		'empresa.razon_social' => 'Empresa',
	];

	/**
	 * Bancos relacionado
	 * @return
	 */
	public function banco()
	{
		return $this->belongsTo(Bancos::class, 'fk_id_banco', 'id_banco');
	}

	/**
	 * Empresa relacionada
	 * @return
	 */
	public function empresa()
	{
		return $this->belongsTo(Empresas::class, 'fk_id_empresa', 'id_empresa');
	}

	/**
	 * Moneda relacionada
	 * @return
	 */
	public function moneda()
	{
		return $this->belongsTo(Monedas::class, 'fk_id_sat_moneda', 'id_moneda');
	}
}
