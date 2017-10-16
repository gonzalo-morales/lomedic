<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\Inventarios\Productos;
use App\Http\Models\ModelCompany;

class ClaveClienteProductos extends ModelCompany
{
	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'pry_cat_clave_cliente_productos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_clave_cliente_producto';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['fk_id_cliente', 'clave_producto_cliente','subclave','descripcion','presentacion',
        'cantidad_presentacion','fk_id_unidad_medida','fk_id_clave_producto_servicio','fk_id_clave_unidad',
        'marca','fabricante','precio','precio_referencia','descuento','descuento_porcentaje','fk_id_impuesto',
        'dispensacion','dispensacion_porcentaje','fk_id_proyecto_tipo_producto','fk_id_propietario',
        'fk_id_tipo_almacen','pertenece_cuadro','minimo','maximo','fk_id_sku','activo'];

	/**
	 * The validation rules
	 * @var array
	 */
	public $rules = [
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = [
	];

	public $niceNames = [
    ];

	function sku()
    {
        return $this->hasOne(Productos::class,'id_sku','fk_id_sku');
    }
}
