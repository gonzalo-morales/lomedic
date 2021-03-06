<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class SeriesDocumentos extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'gen_cat_serie';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_serie';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = [
		'nombre_serie',
		'prefijo',
		'sufijo',
		'primer_numero',
		'siguiente_numero',
		'ultimo_numero',
		'fk_id_empresa',
		'fk_id_tipo_documento',
		'descripcion',
		'activo'
	];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'nombre_serie' => 'max:120|regex:/^[a-zA-Z\s]+/|required',
		'prefijo' => 'max:6|required',
		'sufijo' => 'max:6|required',
		'primer_numero' => 'max:32|numeric|required',
		'siguiente_numero' => 'max:32|numeric|required',
		'ultimo_numero' => 'max:32|numeric|required',
		'fk_id_empresa' => 'required',
		'fk_id_tipo_documento' => 'required',
		'descripcion' => 'max:150|regex:/^[a-zA-Z\s]+/'
	];

    protected $unique = [
		'nombre_serie',
		'prefijo',
		'sufijo',	
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'nombre_serie' => 'Nombre Serie',
	    'prefijo' => 'Prefijo',
	    'sufijo' => 'Sufijo',
	    'primer_numero' => 'Primer Numero',
	    'siguiente_numero' => 'Numero Siguiente',
	    'ultimo_numero' => 'Ultimo Numero',
	    'empresa.nombre_comercial' => 'Empresa',
	    'tipodocumento.nombre_documento' => 'Tipo Documento',
		'activo_span' => 'Estatus',
	];
	
	public function tipodocumento(){
	    return $this->belongsTo(TiposDocumentos::class, 'fk_id_tipo_documento', 'id_tipo_documento');
	}
	
	public function empresa(){
	    return $this->belongsTo(Empresas::class, 'fk_id_empresa', 'id_empresa');
	}
}