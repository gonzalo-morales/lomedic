<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class Empresas extends ModelBase
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'maestro.gen_cat_empresas';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_empresa';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
	    'nombre_comercial', 'rfc', 'empresa', 'razon_social', 'conexion', 'icono', 'activo', 'logotipo', 'fk_id_regimen_fiscal','calle',
	    'no_exterior','no_interior','codigo_postal','colonia','fk_id_municipio','fk_id_estado','fk_id_pais','email'
	];
	
	protected $fields = [
	    'nombre_comercial' => 'Nombre Comercial',
	    'razon_social' => 'Razon Social',
	    'rfc' => 'Rfc',
	    'activo_text' => 'Estatus',
	];
	
	protected $unique = ['rfc','razon_social','nombre_comercial'];
	
	public $rules = [
		'nombre_comercial' => 'required|max:25',
		'rfc' => 'required|max:13',
		'empresa' => 'required',
		'razon_social' => 'required|max:200',
		'conexion' => 'required',
		'fk_id_regimen_fiscal' => 'required',
		'calle'  => 'required|max:60',
		'no_exterior'  => 'required|max:30|numeric',
		'no_interior'  => 'max:30|numeric',
		'codigo_postal'  => 'required|max:10',
		'colonia'  => 'required|max:80',
	];

	/**
	 * Obtenemos empresas activas
	 * @return Collection
	 */
	
	public function regimenfiscal()
	{
	    return $this->hasOne(RegimenesFiscales::class,'id_regimen_fiscal','fk_id_regimen_fiscal');
	}

	/**
	 * Obtenemos los modulos relacionados a la empresa
	 * @return array
	 */
	public function modulos()
	{
		return $this->belongsToMany(Modulos::class, 'ges_det_modulos', 'fk_id_empresa', 'fk_id_modulo');
	}

    public function modulos_empresa()
    {
        return $this->belongsToMany(Modulos::class, 'adm_det_modulo_accion', 'fk_id_empresa', 'fk_id_modulo');
    }

    public function accion_empresa($id_modulo)
    {
        return $this->belongsToMany(Acciones::class, 'adm_det_modulo_accion', 'fk_id_empresa', 'fk_id_accion')
            ->select('id_modulo_accion','nombre')
            ->where('fk_id_modulo','=',$id_modulo)->get();
    }

    public function modulos_usuario($id_usuario,$id_empresa)
    {
        return $this->belongsToMany(Modulos::class, 'adm_det_modulo_accion', 'fk_id_empresa', 'fk_id_modulo')
            ->join('adm_det_permisos_usuarios','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_usuarios.fk_id_modulo_accion')
            ->where('adm_det_permisos_usuarios.fk_id_usuario','=',$id_usuario)
            ->where('adm_det_modulo_accion.fk_id_empresa','=',$id_empresa)
            ->get();
    }

    public function accion_usuario($id_usuario,$id_empresa,$id_modulo)
    {
        return $this->belongsToMany(Acciones::class, 'adm_det_modulo_accion', 'fk_id_empresa', 'fk_id_accion')
            ->join('adm_det_permisos_usuarios','adm_det_modulo_accion.id_modulo_accion','=','adm_det_permisos_usuarios.fk_id_modulo_accion')
            ->select('id_modulo_accion','adm_cat_acciones.nombre')
            ->where('adm_det_permisos_usuarios.fk_id_usuario','=',$id_usuario)
            ->where('adm_det_modulo_accion.fk_id_empresa','=',$id_empresa)
            ->where('adm_det_modulo_accion.fk_id_modulo','=',$id_modulo)
            ->get();
    }
    
    public function certificados(){
        return $this->hasMany(Certificados::class,'fk_id_empresa');
    }

    public function numeroscuenta()
    {
        return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
    }

	/**
	 * Obtenemos los modulos anidados relacionados a la empresa
	 * @return array
	 */
	public function modulos_anidados()
	{
		return $this->__modulos();
	}

	/**
	 * Obtenemos los modulos anidados relacionados a la empresa
	 * @param  Modulo $modulo
	 * @return array
	 */
	private function __modulos($modulo = null)
	{
		#
		$collection = collect([]);
		# Obtenemos modulos hijos donde ...
		$modulos = Modulos::whereHas('empresas', function($q) use ($modulo) {
			if (!$modulo) {
				$q->whereNull('fk_id_modulo_hijo');
			} else {
				$q->whereIn('fk_id_modulo_hijo', [$modulo->id_modulo]);
			}
			# Modulos relacionados a empresa
			$q->where('fk_id_empresa', $this->id_empresa );
		})->get();
		# Recorremos modulos
		foreach ($modulos as $modulo) {
			$modulo->submodulos = $this->__modulos($modulo);
			$collection[] = $modulo;
		}
		return collect($collection);
	}
	public function sucursales()
	{
		return $this->belongsToMany(Sucursales::class,'maestro.adm_det_empresa_sucursal', 'fk_id_empresa','fk_id_sucursal');
	}
	/*relación de tres*/
	public function usuario_empresa()
	{
		return $this->hasMany(Usuarios::class,'maestro.adm_det_empresa_sucursal_usuario','fk_id_empresa','fk_id_usuario');
	}
	public function empresa_sucursales()
	{
		return $this->hasMany(Sucursales::class,'maestro.adm_det_empresa_sucursal_usuario','fk_id_empresa','fk_id_sucursal');
	}
}
