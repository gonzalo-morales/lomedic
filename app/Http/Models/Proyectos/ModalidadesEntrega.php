<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelBase;

class ModalidadesEntrega extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'maestro.pry_cat_modalidades_entrega';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_modalidad_entrega';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['modalidad_entrega','activo','eliminar'];

	public $niceNames = [
	    'modalidad_entrega' => 'modalidad entrega'
    ];

	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_modalidad_entrega');
    }
}
