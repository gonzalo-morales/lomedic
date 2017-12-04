<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Estados extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_estados';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_estado';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['estado', 'fk_id_pais', 'activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'estado' => 'required',
		'fk_id_pais' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'estado' => 'Entidad',
		'pais.pais' => 'País',
		'activo_span' => 'Estatus',
	];

	/**
	 * Atributos de carga optimizada
	 * @var array
	 */
	protected $eagerLoaders = ['pais'];

	/**
	 * Obtenemos empresas activas
	 * @return Collection
	 */
	public static function active()
	{
		return self::where('activo','=','1')->get();
	}

	/**
	 * Obtenemos municipios relacionados
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function municipios() {
		return $this->hasMany(Municipios::class, 'fk_id_estado', 'id_estado');
	}

	/**
	 * Obtenemos pais relacionado
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function pais(){
		return $this->belongsTo(Paises::class, 'fk_id_pais', 'id_pais');
	}
}
