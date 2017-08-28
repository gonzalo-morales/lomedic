<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;

class Estados extends ModelBase
{
    // use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_estados';

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
		'paises' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'estado' => 'Entidad',
		'pais_pais' => 'País',
		'activo_span' => 'Estatus',
	];

	public function getPaisPaisAttribute()
	{
		return $this->pais ? $this->pais->pais : '';
	}

	/**
	 * Obtenemos empresas activas
	 * @return Collection
	 */
	public static function active()
	{
		return self::where('activo','=','1')->get();
	}

	/**
	 * Un estado puede tener muchos municipios
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function municipios(){
		return $this->hasMany('App\Http\Models\Administracion\Municipios');
	}

	/**
	 * Obtenemos pais relacionado
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function pais(){
		return $this->belongsTo(Paises::class, 'fk_id_pais', 'id_pais');
	}
}
