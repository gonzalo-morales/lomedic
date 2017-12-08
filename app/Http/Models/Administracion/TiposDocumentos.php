<?php
namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposDocumentos extends ModelBase
{
    protected $table = 'maestro.gen_cat_tipo_documento';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_tipo_documento';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['nombre_documento','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'nombre_documento' => 'required'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'nombre_documento' => 'Documento',
		'activo_span' => 'Estado'
	];
}