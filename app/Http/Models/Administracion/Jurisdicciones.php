<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Jurisdicciones extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_jurisdicciones';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_jurisdiccion';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['jurisdiccion', 'fk_id_estado','activo'];

	protected $unique = ['jurisdiccion'];
	
	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'jurisdiccion' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'jurisdiccion' => 'JurisdicciÃ³n',
		'estado.estado' => 'Estado',
		'activo_span' => 'Estatus',
	];

	/**
	 * Obtenemos entidad federativa relacionada
	 * @return \Illuminate\Database\Eloquent\Relations\belongsTo
	 */
	public function estado()
	{
		return $this->belongsTo('App\Http\Models\Administracion\Estados', 'fk_id_estado', 'id_estado');
	}
	
	public function sucursales() {
	    return $this->hasOne(Sucursales::class, 'fk_id_jurisdiccion', 'id_jurisdiccion');
	}
}
