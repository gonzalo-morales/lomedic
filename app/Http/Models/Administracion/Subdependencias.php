<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Subdependencias extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_subdependencias';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_subdependencia';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['subdependencia','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
        'subdependencia' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
        'subdependencia' => 'Subdependencia',
        'activo_span' => 'Estatus'
	];

	public $niceNames = [
	    'subdependencia' => 'Subdependencia'
    ];

	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_subdependencia');
    }
}
