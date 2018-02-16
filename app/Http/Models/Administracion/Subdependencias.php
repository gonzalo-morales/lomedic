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
	protected $fillable = ['subdependencia','activo','fk_id_dependencia'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
        'subdependencia' => 'required|max:40|regex:/^[a-zA-Z\s]+/',
        'fk_id_dependencia' => 'required'
	];

	protected $unique = ['subdependencia'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
        'subdependencia' => 'Subdependencia',
        'dependencia.dependencia' => 'Dependencia',
        'activo_span' => 'Estatus'
	];

	public $niceNames = [
	    'subdependencia' => 'subdependencia',
        'fk_id_dependencia' => 'dependencia'
    ];

	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_subdependencia');
    }

    function dependencia()
    {
        return $this->belongsTo(Dependencias::class,'fk_id_dependencia','id_dependencia');
    }
}
