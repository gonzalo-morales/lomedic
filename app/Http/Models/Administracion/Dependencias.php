<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\Facades\Validator;

class Dependencias extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'gen_cat_dependencias';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_dependencia';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['dependencia','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
        'dependencia' => ['required','max:40','regex:/^[a-zA-Z\d\s{0-9}]*$/'],
	];

	protected $unique = ['dependencia'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
        'dependencia' => 'Dependencia',
        'activo_span' => 'Estatus'
	];

	public $niceNames = [
	    'dependencia' => 'Dependencia'
    ];

	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_dependencia');
    }

    function subdependencias()
    {
        return $this->hasMany(Parentescos::class,'fk_id_dependencia','id_parentesco');
    }
}
