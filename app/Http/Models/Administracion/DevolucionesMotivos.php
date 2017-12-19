<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class DevolucionesMotivos extends ModelBase
{
	protected $table = 'gen_cat_devoluciones_motivos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_devolucion_motivo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['devolucion_motivo', 'solicitante_devolucion','activo'];//Solicitante devoluciÃ³n. false: localidad; true: proveedor;

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'devolucion_motivo' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
        'solicitante_devolucion' => 'required|numeric'
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'devolucion_motivo' =>  'Motivo',
		'solicitante_formated' => 'Devolucion A',
		'activo_span' => 'Estatus'
	];

	public function getSolicitanteFormatedAttribute()
	{
		return $this->solicitante_devolucion ? 'Proveedor' : 'Sucursal';
	}
}
