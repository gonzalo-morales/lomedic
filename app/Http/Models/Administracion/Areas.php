<?php
namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Areas extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'gen_cat_areas';

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
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $unique = ['area','clave_area'];

	/**
	 * Los atributos que seran visibles en smart-datatable
	 * @var array
	 */
	protected $fields = [
		'area' => 'Area',
		'clave_area' => 'Clave',
	    'activo_span' => 'Estatus'
	];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'area' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:90',
		'clave_area' => 'required',
	];
}