<?php
namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class TiposDocumentos extends ModelBase
{
    protected $table = 'gen_cat_tipo_documento';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_tipo_documento';

	/**
	 * The attributes that are mass assignable.
	 * @var array
	 */
	protected $fillable = ['nombre_documento','activo','tabla'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'nombre_documento' => 'required|max:150'
	];

    protected $unique = ['nombre_documento'];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'nombre_documento' => 'Nombre documento',
		'activo_span' => 'Estatus'
	];

    public function estatus()
    {
        return $this->belongsToMany(EstatusDocumentos::class,'gen_det_estatus_tipo_documento','fk_id_tipo_documento','fk_id_estatus','id_tipo_documento','id_estatus');
    }
}