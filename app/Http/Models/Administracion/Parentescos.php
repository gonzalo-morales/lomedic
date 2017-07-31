<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Parentescos extends Model
{
    // use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_parentescos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_parentesco';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre', 'activo'];

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
		'parentesco'	=> 'required',
		// 'activo'		=> 'required',
	];


	/**
	 * @return field name of table
	 */
	public function getTable(){
	    return $this->table;
    }

}
