<?php

namespace App\Http\Models\Servicios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Proyectos\ClaveClienteProductos;

class RecetasDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rec_det_recetas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_receta_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_receta','fk_id_cuadro','cantidad_pedida','cantidad_surtida','dosis',
        'en_caso_presentar','recurrente','fecha_surtido','veces_surtir','veces_surtidas','eliminar','fk_id_clave_cliente_producto'];

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
