<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelBase;

class CaracterEventos extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_caracter_eventos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_caracter_evento';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['caracter_evento','activo'];

	public $niceNames = [
	    'caracter_evento' => 'caracter evento'
    ];

	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_caracter_evento');
    }
}
