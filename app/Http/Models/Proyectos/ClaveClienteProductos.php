<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Stock;
use App\Http\Models\ModelCompany;

class ClaveClienteProductos extends ModelCompany
{
	protected $table = 'pry_cat_clave_cliente_productos';

	protected $primaryKey = 'id_clave_cliente_producto';

	protected $fillable = ['id_clave_cliente_producto','fk_id_cliente', 'clave_producto_cliente','subclave','descripcion','presentacion',
        'cantidad_presentacion','fk_id_unidad_medida','fk_id_clave_producto_servicio','fk_id_clave_unidad',
        'marca','fabricante','precio','precio_referencia','descuento','descuento_porcentaje','fk_id_impuesto',
        'dispensacion','dispensacion_porcentaje','fk_id_proyecto_tipo_producto','fk_id_propietario',
        'fk_id_tipo_almacen','pertenece_cuadro','minimo','maximo','fk_id_sku','activo','fk_id_upc'
    ];

	public $rules = [
	];

	protected $fields = [
	];

	public $niceNames = [
    ];

	function sku()
    {
        return $this->hasOne(Productos::class,'id_sku','fk_id_sku');
    }
    public function stock($sku,$upc)
    {
        return $this->hasOne(Stock::class,'fk_id_sku','fk_id_sku')->where('fk_id_sku',$sku)->where('fk_id_upc',$upc)->pluck('stock');
    }
}
