<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\Upcs;

class ProductosSociosNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_det_productos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_producto';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fk_id_socio_negocio', 'fk_id_sku', 'fk_id_upc', 'tiempo_entrega', 'precio', 'precio_de', 'precio_hasta', 'activo'];

    /**
     * Indicates if the model should be timestamped.
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules
     * @var array
     */
    public $rules = [];

    public function sociosnegocio(){
        return $this->belongsTo(SociosNegocio::class,'fk_id_socio_negocio');
    }

    public function sku(){
        return $this->belongsTo(Productos::class,'fk_id_sku');
    }
    
    public function upc(){
        return $this->belongsTo(Upcs::class,'fk_id_pc');
    }
}