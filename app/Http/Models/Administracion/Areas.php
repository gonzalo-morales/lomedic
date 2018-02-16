<?php
namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Areas extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_areas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_area';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['area', 'clave_area','activo'];

	/**
	 * Indicates if the model should be timestamped.
	 * @var bool
	 */
	public $timestamps = false;

   /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $unique = ['area','clave_area'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'area' => 'required|max:90|regex:/^[a-zA-Z\s]+/',
		'clave_area' => 'required|max:32|regex:/^[a-zA-Z\s]+/',
	];

	/**
	 * Los atributos que seran visibles en smart-datatable
	 * @var array
	 */
	protected $fields = [
		'area' => 'Area',
		'clave_area' => 'Clave',
	    'activo_span' => 'Estatus'
	];
}