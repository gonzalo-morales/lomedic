<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposDocumentos extends ModelBase
{
	protected $table = 'gen_cat_tipo_documento';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_tipo_documento';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre_documento'];//Solicitante devolución. false: localidad; true: proveedor;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'nombre_documento' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'nombre_documento' => 'Documento'
	];

}
