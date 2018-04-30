<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;
use App\Http\Models\Inventarios\DetallePresentaciones;

class Sales extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'adm_cat_sales';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_sal';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['nombre','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'nombre'=>'required|max:80'
	];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
		'nombre' => 'Nombre',
        'activo_span' => 'Estatus'
	];
	public function presentacion()
	{
		return $this->hasOne(DetallePresentaciones::class,'fk_id_sal','id_sal');
	}
}