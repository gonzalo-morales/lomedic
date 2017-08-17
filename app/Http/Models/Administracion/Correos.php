<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;

class Correos extends ModelBase
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ges_det_correos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_correo';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['correo', 'fk_id_empresa', 'fk_id_usuario','activo'];

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
		'correo' => 'required|email',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'correo' => 'Correo',
		'empresa.nombre_comercial' => 'Empresa',
		'usuario.usuario' => 'Usuario',
		'activo_span' => 'Estado',
	];

	public function getActivoFormatedAttribute()
	{
		return $this->activo ? 'Activo' : 'Inactivo';
	}

	public function getActivoSpanAttribute()
	{
		return new HtmlString("<span class=" . ($this->activo ? 'toast_success' : 'toast_error') . ">&nbsp;$this->activo_formated&nbsp;</span>");
	}

	/**
	 * Obtenemos usuario relacionado
	 * @return Usuario
	 */
	public function usuario()
	{
		return $this->belongsTo(Usuarios::class, 'fk_id_usuario', 'id_usuario');
	}

	/**
	 * Obtenemos empresa relacionada
	 * @return Empresa
	 */
	public function empresa()
	{
		return $this->belongsTo(Empresas::class, 'fk_id_empresa', 'id_empresa');
	}
}
