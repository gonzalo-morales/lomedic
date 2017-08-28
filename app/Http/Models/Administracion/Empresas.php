<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_empresas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_empresa';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	// protected $fillable = ['razon_social', 'banco', 'rfc', 'nacional'];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Obtenemos empresas activas
	 * @return Collection
	 */
	public static function active()
	{
		return self::where('activo','=','1');
	}

	/**
	 * Obtenemos los modulos relacionados a la empresa
	 * @return array
	 */
	public function modulos()
	{
		return $this->belongsToMany(Modulos::class, 'ges_det_modulos', 'fk_id_empresa', 'fk_id_modulo');
	}

	public function numeroscuenta()
    {
        return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
    }

	/**
	 * Obtenemos los modulos anidados relacionados a la empresa
	 * @return array
	 */
	public function modulos_anidados()
	{
		return $this->__modulos();
	}

	/**
	 * Obtenemos los modulos anidados relacionados a la empresa
	 * @param  Modulo $modulo
	 * @return array
	 */
	private function __modulos($modulo = null)
	{
		#
		$collection = collect([]);
		# Obtenemos modulos hijos donde ...
		$modulos = Modulos::whereHas('empresas', function($q) use ($modulo) {
			if (!$modulo) {
				$q->whereNull('fk_id_modulo_hijo');
			} else {
				$q->whereIn('fk_id_modulo_hijo', [$modulo->id_modulo]);
			}
			# Modulos relacionados a empresa
			$q->where('fk_id_empresa', $this->id_empresa );
		})->get();
		# Recorremos modulos
		foreach ($modulos as $modulo) {
			$modulo->submodulos = $this->__modulos($modulo);
			$collection[] = $modulo;
		}
		return collect($collection);
	}

}
