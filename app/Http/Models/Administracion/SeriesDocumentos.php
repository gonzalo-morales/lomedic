<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelCompany;

class SeriesDocumentos extends ModelCompany
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_serie';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_serie';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['nombre_serie','prefijo','sufijo','primer_numero','siguiente_numero','ultimo_numero','fk_id_empresa','fk_id_tipo_documento','descripcion','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [];

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