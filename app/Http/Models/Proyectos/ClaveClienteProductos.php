<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\Impuestos;
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

	protected $fillable = ['fk_id_cliente', 'clave_producto_cliente','subclave','descripcion','presentacion',
        'cantidad_presentacion','fk_id_unidad_medida','fk_id_clave_producto_servicio','fk_id_clave_unidad',
        'marca','fabricante','precio','precio_referencia','descuento','descuento_porcentaje','fk_id_impuesto',
        'dispensacion','dispensacion_porcentaje','fk_id_proyecto_tipo_producto','fk_id_propietario',
        'fk_id_tipo_almacen','pertenece_cuadro','minimo','maximo','fk_id_sku','activo','tope_receta','disponibilidad',
        'fk_id_upc'];

	public $rules = [
        'cantidad_presentacion' => 'required|numeric|min:1',
        'descuento' => ['required','less_than_field:precio','less_than_field:precio_referencia','regex:/^([1-9][0-9]{1,10})(\.[0-9]{2})?$/'],
        'descuento_porcentaje' => ['required','numeric','between:0,100'],
        'dispensacion' => ['required','numeric','between:0,100'],
        'dispensacion_porcentaje' => ['required','numeric','between:0,99.9999999999'],
        'minimo' => ['required','numeric','min:1'],
        'maximo' => ['required','numeric','min:1','greater_than_field:minimo'],
        'tope_receta' => ['required','numeric','min:1','digits_between:1,16'],
    ];

	protected $fields = [
	    'cliente.nombre_comercial' => 'Cliente',
	    'clave_producto_cliente' => 'Clave Cliente',
	    'subclave' => 'Subclave',
	    'descripcion' => 'Descripcion',
	    'presentacion' => 'Presentacion',
	    'marca' => 'Marca',
	    'fabricante' => 'Fabricante',
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

    public function producto()
    {
        return $this->hasOne(Productos::class,'id_sku','fk_id_sku');
    }

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
}