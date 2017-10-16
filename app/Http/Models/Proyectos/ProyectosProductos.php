<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\ModelCompany;
use DB;

class ProyectosProductos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pry_cat_proyectos_productos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_proyecto_producto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_clave_cliente_producto','fk_id_upc','prioridad','cantidad','precio_sugerido','activo'];

    public $niceNames =[
        'fk_id_clave_cliente_producto'=>'clave cliente producto',
        'fk_id_upc'=>'upc',
        'precio_sugerido' => 'precio sugerido'
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
//        'prioridad' => 'required|numeric',
//        'cantidad' => 'required|numeric',
//        'precio_sugerido' => 'requried|regex:/^[0-9]+(\.[0-9]{1,2})?$/'
    ];

    public $fields = [
        'id_proyecto_producto' => 'Proyecto',
        'cliente.nombre_corto' => 'Cliente',
        'proyecto.propyecto' => 'Proyecto',
        'activo_span' => 'Estatus'
    ];

    function claveClienteProducto()
    {
        return $this->hasOne(ClaveClienteProductos::class,'id_clave_cliente_producto','fk_id_clave_cliente_producto');
    }

    function upc()
    {
        return $this->hasOne(Upcs::class,'id_upc','fk_id_upc');
    }
}
