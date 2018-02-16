<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class FormasAdjudicacion extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_formas_adjudicacion';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_forma_adjudicacion';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['forma_adjudicacion','activo'];

	public $rules = [
	    "forma_adjudicacion" => "required|max:255"
    ];

	protected $unique = ['forma_adjudicacion'];

	protected $fields = [
	    'forma_adjudicacion' => 'Forma Adjudicación',
        'activo_span' => 'Estatus'
    ];

	public $niceNames = [
	    'forma_adjudicacion' => 'forma adjudicacion'
    ];

	function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'fk_id_forma_adjudicacion');
    }
}
