<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Upcs;
use App\Http\Models\ModelBase;
use App\Http\Models\Proyectos\ClaveClienteProductos;

class Especificaciones extends ModelBase
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_especificaciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_especificacion';

    protected $fields = [
        'especificacion' => 'Especificación',
        'activo_span' => 'Activo'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['especificacion','activo'];

    public function skus()
    {
        return $this->belongsToMany(Productos::class,'inv_det_especificaciones_producto','fk_id_especificacion','fk_id_sku','id_especificacion','id_sku');
    }

    public function upcs()
    {
        return $this->belongsToMany(Upcs::class,'inv_det_especificaciones_upc','fk_id_especificacion','fk_id_upc','id_especificacion','id_upc');
    }

    public function clavescliente()
    {
        return $this->belongsToMany(ClaveClienteProductos::class,'inv_det_especificaciones_clave_cliente','fk_id_especificacion','fk_id_clave_cliente','id_especificacion','id_clave_cliente_producto');
    }
}