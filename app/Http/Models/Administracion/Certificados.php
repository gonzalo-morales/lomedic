<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Certificados extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_det_certificados';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_certificado';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['archivo', 'fecha_expedicion', 'fecha_vencimiento', 'activo'];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [];
	/*	'area' => ['required','max:50','unique','12'],
        'clave_area'=>['numeric'],
	];*/

	/**
	 * Los atributos que seran visibles en smart-datatable
	 * @var array
	 */
	protected $fields = [
		'archivo' => 'Archivo',
		'fecha_expedicion' => 'Fecha Expedicion',
	    'fecha_vencimiento' => 'Fecha Vencimiento'
	];
	
	public function empresa(){
	    return $this->belongsTo(Empresas::class,'fk_id_empresa');
	}
}