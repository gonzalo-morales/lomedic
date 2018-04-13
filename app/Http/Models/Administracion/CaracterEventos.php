<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class CaracterEventos extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'gen_cat_caracter_eventos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_caracter_evento';

	protected $fields = ['id_caracter_evento'=>'#','caracter_evento'=>'Carácter Evento','activo_span'=>'Estatus'];

	protected $unique = ['caracter_evento'];
	
	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['caracter_evento','activo'];

	public $rules = ['caracter_evento'=>'required|max:255'];

	public $niceNames = [
	    'caracter_evento' => 'caracter evento'
    ];

	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_caracter_evento');
    }
}
