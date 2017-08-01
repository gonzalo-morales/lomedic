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
	 * Obtenemos los modulos relacionados a la empresa
	 * @return array
	 */
	public function modulos()
	{
		return $this->belongsToMany('App\Http\Models\Administracion\Modulos', 'ges_det_modulos_empresas', 'fk_id_empresa', 'fk_id_modulo');
	}

	public function numeroscuenta()
    {
        return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
    }



}
