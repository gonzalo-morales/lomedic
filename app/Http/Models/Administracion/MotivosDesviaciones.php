<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class MotivosDesviaciones extends Model
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_motivos_desviaciones';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_motivo_desviacion';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['descripcion', 'activo'];

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
	public $rules = [
		'descripcion'	=> 'required',
		// 'activo'		=> 'required',
	];


	/**
	 * @return field name of table
	 */
	public function getTable(){
	    return $this->table;
    }

}
