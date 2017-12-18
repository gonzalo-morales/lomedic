<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\ClavesProductosServicios;
use App\Http\Models\Administracion\ClavesUnidades;
use App\Http\Models\Administracion\Impuestos;
use App\Http\Models\ModelCompany;
use DB;

class DetalleNotasCreditoProveedor extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fac_det_notas_credito_proveedor';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_nota_credito_proveedor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'importe',
        'precio_unitario',
        'descripcion',
        'fk_id_clave_unidad',
        'fk_id_clave_producto_servicio',
        'cantidad',
        'fk_id_impuesto',
        'descuento',
        'fk_id_documento',
        'unidad'
    ];

    public function clave_unidad()
    {
        return $this->hasOne(ClavesUnidades::class,'id_clave_unidad','fk_id_clave_unidad');
    }

    public function clave_producto_servicio()
    {
        return $this->hasOne(ClavesProductosServicios::class,'id_clave_producto_servicio','fk_id_clave_producto_servicio');
    }

    public function impuesto()
    {
        return $this->hasOne(Impuestos::class,'id_impuesto','fk_id_impuesto');
    }

    public function notacredito()
    {
        return $this->belongsTo(NotasCreditoProveedor::class,'id_nota_credito_proveedor','fk_id_nota_credito_proveedor');
    }
}
