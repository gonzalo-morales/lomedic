<?php

namespace App\Http\Models\Soporte;

use App\Http\Models\ModelBase;

class EstatusTickets extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'sop_cat_estatus_tickets';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_estatus_ticket';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['estatus', 'activo'];
	
	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
	    'estatus' => 'Estatus',
	    'activo_span' => 'Estatus'
	];

    protected $unique = ['estatus'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'estatus' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/|max:255',
	];

	public function solicitudes()
    {
        return $this->belongsToMany('App\Http\Models\Soporte\Solicitudes');
    }
}
