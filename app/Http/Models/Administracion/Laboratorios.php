<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;
use App\Http\Models\Inventarios\Upcs;

class Laboratorios extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_laboratorios';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_laboratorio';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['laboratorio','activo'];

	protected $unique = ['laboratorio'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'laboratorio' => ['required','regex:/^[a-zA-Z\s]+/','max:150']
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'laboratorio' => 'Laboratorio',
		'activo_span' => 'Estatus',
	];

	public function usuario()
	{
		$this->hasOne('app\Http\Models\Administracion\Usuarios');
	}

	public function empresa()
	{
		$this->$this->hasOne('app\Http\Models\Administracion\Empresas');
	}
	
	public function upcs() {
	    $this->hasMany(Upcs::class, 'id_laboratorio','fk_id_laboratorio' );
	}
	
}
