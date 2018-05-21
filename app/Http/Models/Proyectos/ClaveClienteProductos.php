<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\Especificaciones;
use App\Http\Models\Administracion\FormaFarmaceutica;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\Administracion\Presentaciones;
use App\Http\Models\Administracion\Sales;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\ModelCompany;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Inventarios\Stock;

class ClaveClienteProductos extends ModelCompany
{

	protected $table = 'pry_cat_clave_cliente_productos';

	protected $primaryKey = 'id_clave_cliente_producto';

    protected $fillable = ['fk_id_cliente', 'clave_producto_cliente','subclave','descripcion','fk_id_presentacion',
        'fk_id_forma_farmaceutica','fk_id_unidad_medida','fk_id_clave_producto_servicio','fk_id_clave_unidad','precio',
        'fk_id_impuesto','activo','tope_receta','fk_id_subgrupo','pertenece_cuadro','fraccionado'];

	public $rules = [
        'tope_receta' => ['required','numeric','min:1','digits_between:1,16'],
        'clave_producto_cliente' => 'required|max:20',
        'subclave' => 'required|max:20'
    ];

	protected $fields = [
	    'cliente.nombre_comercial' => 'Cliente',
	    'clave_producto_cliente' => 'Clave Cliente',
	    'subclave' => 'Subclave',
	    'descripcion' => 'Descripcion',
	    'presentacion_text' => 'Presentacion',
	];

	public $niceNames = [];

	function sku()
    {
        return $this->hasOne(Productos::class,'id_sku','fk_id_sku');
    }
    
    function cliente()
    {
        return $this->hasOne(SociosNegocio::class,'id_socio_negocio','fk_id_cliente');
    }

    public function stock($sku,$upc)
    {
        return $this->hasOne(Stock::class,'fk_id_sku','fk_id_sku')->where('fk_id_sku',$sku)->where('fk_id_upc',$upc)->pluck('stock');
    }
    public function proyectos()
    {
        return $this->belongsToMany(Proyectos::class,'pry_cat_proyectos_productos','fk_id_clave_cliente_producto','fk_id_proyecto','id_clave_cliente_producto','id_proyecto');
    }

    public function proyectoproducto()
    {
        return $this->hasMany(ProyectosProductos::class,'fk_id_clave_cliente_producto','id_clave_cliente_producto');
    }

    // public function producto()
    // {
    //     return $this->hasOne(Productos::class,'id_sku','fk_id_sku');
    // }

    public function impuesto()
    {
        return $this->hasOne(Impuestos::class,'id_impuesto','fk_id_impuesto');
    }

    public function claveproductoservicio()
    {
        return $this->hasOne(ClavesProductosServicios::class,'id_clave_producto_servicio','fk_id_clave_producto_servicio');
    }

    public function claveunidad()
    {
        return $this->hasOne(ClavesUnidades::class,'id_clave_unidad','fk_id_clave_unidad');
    }

    public function upc()
    {
        return $this->hasOne(Upcs::class,'id_upc','fk_id_upc');
    }

    public function concentraciones()
    {
        return $this->hasMany(ClaveClienteSalConcentracion::class,'fk_id_clave_cliente_producto','id_clave_cliente_producto');
    }

    public function presentacion()
    {
        return $this->hasOne(Presentaciones::class,'id_presentacion','fk_id_presentacion');
    }

    public function formafarmaceutica()
    {
        return $this->hasOne(FormaFarmaceutica::class,'id_forma_facmaceutica','fk_id_forma_farmaceutica');
    }

    public function getPresentacionTextAttribute()
    {
        if(!empty($this->fk_id_presentacion))
            return ucwords(strtolower($this->presentacion->unidad->nombre));
        else
            return '';
    }

    public function productos()
    {
        return $this->belongsToMany(Upcs::class,'inv_det_upc_clave_cliente','fk_id_clave_cliente','fk_id_upc','id_clave_cliente_producto','id_upc');
    }

    public function especificaciones()
    {
        return $this->belongsToMany(Especificaciones::class,'inv_det_especificaciones_clave_cliente','fk_id_clave_cliente','fk_id_especificacion','id_clave_cliente_producto','id_especificacion');
    }
}