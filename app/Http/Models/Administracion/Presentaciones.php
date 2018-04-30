<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;
use App\Http\Models\Administracion\UnidadesMedidas;

class Presentaciones extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'adm_cat_presentaciones';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_presentacion';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['cantidad','fk_id_unidad_medida','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'cantidad'	=> 'required',
		'fk_id_unidad_medida' => 'required'
	];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
		'cantidad' => 'Cantidad',
		'unidad.clave' => 'Unidad de Medida',
        'activo_span' => 'Estatus'
	];

	public function unidad()
    {
        return $this->hasOne(UnidadesMedidas::class,'id_unidad_medida');
    }
}