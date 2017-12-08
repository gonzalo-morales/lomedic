<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Certificados extends ModelBase
{
	/**
	 * The table associated with the model.
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
	 * @var array
	 */
	protected $fillable = ['key','certificado','no_certificado','cadena_cer','fecha_expedicion','fecha_vencimiento','activo'];

	/**
	 * Indicates if the model should be timestamped.
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [];

	/**
	 * Los atributos que seran visibles en smart-datatable
	 * @var array
	 */
	protected $fields = [];
	
	public function empresa(){
	    return $this->belongsTo(Empresas::class,'fk_id_empresa');
	}
}