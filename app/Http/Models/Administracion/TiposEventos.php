<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposEventos extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'maestro.pry_cat_tipos_eventos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_tipo_evento';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['tipo_evento','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
        'tipo_evento' => 'required|max:40|regex:/^[a-zA-Z\s]+/',
	];

    protected $unique = ['tipo_evento'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
        'tipo_evento' => 'Tipo Evento',
        'activo_span' => 'Estatus'
	];

	public $niceNames = [
	    'tipo_evento' => 'Tipo Evento'
    ];

	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_tipo_evento');
    }
}
