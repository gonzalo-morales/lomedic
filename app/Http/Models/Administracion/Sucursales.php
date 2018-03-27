<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use App\Http\Models\Inventarios\Almacenes;
use App\Http\Models\RecursosHumanos\Empleados;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Soporte\Solicitudes;

class Sucursales extends ModelBase
{
	/**
	 * The table associated with the model.
	 * @var string
	 */
	protected $table = 'ges_cat_sucursales';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_sucursal';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'sucursal',
		'fk_id_tipo',
		'fk_id_localidad',
		'fk_id_zona',
		'fk_id_cliente',
		'responsable',
		'telefono_1',
		'telefono_2',
		'calle',
		'numero_interior',
		'numero_exterior',
		'colonia',
		'codigo_postal',
		'fk_id_pais',
		'fk_id_estado',
		'fk_id_municipio',
		'latitud',
		'longitud',
		'registro_sanitario',
		'inventario',
		'enbarque',
		'tipo_batallon',
		'region',
		'zona_militar',
		'clave_presupuestal',
		'id_sucursal_proveedor',
		'fk_id_jurisdiccion',
		'activo'
	];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
		'sucursal' => 'required|max:255',
		'fk_id_tipo' => 'required',
		'fk_id_localidad' => 'required',
		'fk_id_zona' => 'required',
		'fk_id_cliente' => 'required',
		'calle' => 'required|max:255',
		'numero_exterior' => 'required',
		'colonia' => 'required|max:30',
		'codigo_postal' => 'required|max:5|numeric',
		'fk_id_pais' => 'required',
		'fk_id_estado' => 'required',
		'fk_id_municipio' => 'required',
	];

	public $niceNames = [
		'fk_id_localidad' => 'localidad',
	];

	protected $unique = [
		'sucursal',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
		'sucursal' => 'Sucursal',
		'localidad.localidad' => 'Localidad',
	    'cliente.nombre_comercial' => 'Cliente',
		'tiposucursal.tipo' => 'Tipo de Sucursal',
		'activo_span' => 'Estatus',
	];

	public function tiposucursal() {
		return $this->hasOne(TipoSucursal::class, 'id_tipo', 'fk_id_tipo');
	}

	public function localidad() {
		return $this->hasOne(Localidades::class, 'id_localidad', 'fk_id_localidad');
	}

	public function solicitudes()
	{
		return $this->hasMany(Solicitudes::class,'fk_id_sucursal','id_sucursal');
	}

	public function almacenes()
	{
		return $this->hasMany(Almacenes::class, 'fk_id_sucursal', 'id_sucursal');
	}

	public function empleados()
    {
        return $this->belongsToMany(Empleados::class,$this->schema.'.ges_det_empleado_sucursal','fk_id_sucursal','fk_id_empleado','id_sucursal','id_empleado');
    }

    public function cliente()
    {
        return $this->belongsTo(SociosNegocio::class,'fk_id_cliente');
	}
	public function empresas()
	{
	    return $this->belongsToMany(Empresas::class,$this->schema.'.adm_det_empresa_sucursal', 'fk_id_sucursal','fk_id_empresa');
	}
	/*relación de tres*/
	public function usuario()
	{
	    return $this->belongsToMany(Usuarios::class,$this->schema.'.adm_det_empresa_sucursal_usuario','fk_id_sucursal','fk_id_usuario');
		//->withPivot('fk_id_empresa')
        //->join(Empresas::class,'fk_id_empresa','=','id_empresa');
	}
	public function empresa()
	{
	    return $this->belongsToMany(Empresas::class,$this->schema.'.adm_det_empresa_sucursal_usuario','fk_id_sucursal','fk_id_empresa');
        //->withPivot('fk_id_usuario')
        //->join(Usuarios::class,'fk_id_usuario','=','id_usuario');
	}
}