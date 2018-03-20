<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class ModalidadesEntrega extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'pry_cat_modalidades_entrega';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_modalidad_entrega';

	protected $fields = [
	    'modalidad_entrega'=>'Modalidad Entrega',
        'activo_span' => 'estatus'
    ];

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['modalidad_entrega','activo'];

    public $rules = [
        'modalidad_entrega' => 'required|max:255'
    ];

	public $niceNames = [
	    'modalidad_entrega' => 'modalidad entrega'
    ];

    protected $unique = ['modalidad_entrega'];


	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_modalidad_entrega');
    }
}
