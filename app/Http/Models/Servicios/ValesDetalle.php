<?php

namespace App\Http\Models\Servicios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Proyectos\ClaveClienteProductos;

class ValesDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rec_det_vales';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_vale';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
       'fk_id_vale',
       'fk_id_clave_cliente_producto',
       'cantidad_solicitada',
       'cantidad_surtida',
       'eliminar'
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
//        'correo' => 'required|email',
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
    ];

    public function receta()
    {
        return $this->belongsTo(Recetas::class,'fk_id_receta','id_receta');
    }

    public function producto()
    {
        return $this->hasOne(Productos::class,'id_sku','fk_id_sku');
    }
    public function claveClienteProducto()
    {
        return $this->hasOne(ClaveClienteProductos::class,'id_clave_cliente_producto','fk_id_clave_cliente_producto');
    }

}
