<?php

namespace App\Http\Models\Compras;

use App\Http\Models\ModelCompany;
use DB;

class DetalleSeguimientoDesviacion extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_det_seguimiento_desviacion';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_seguimiento_desviacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_seguimiento_desviacion','fk_id_orden_compra','fk_id_detalle_orden_compra','fk_id_entrada','fk_id_detalle_entrada','cantidad_orden_compra','cantidad_entrada',
                            'cantidad_desviacion','fk_id_factura_proveedor','fk_id_detalle_factura_proveedor','precio_orden_compra','precio_factura','precio_desviacion'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        // 'id_seguimiento_desviacion' => 'ID',
        // 'fk_id_proveedor'           => 'Proveedor',
        // 'serie_factura'             => 'Serie Factura',
        // 'folio_factura'             => 'Folio Factura',
    ];

    // public function socios()
    // {
    //     return $this->hasMany('App\Http\Models\SociosNegocio\SociosNegocio','fk_id_proveedor', 'id_socio_negocio');
    // }
    // public function usuarios()
    // {
    //     return $this->hasOne(Usuarios::class,'fk_id_usuario_captura','id_usuario');
    // }
    // public function proveedor()
    // {
    //     return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_socio_negocio');
    // }
    // public function tipoDocumento()
    // {
    //     return $this->belongsTo('App\Http\Models\Administracion\TiposDocumentos','fk_id_tipo_documento','id_tipo_documento');
    // }
    public function detalleOrden()
    {
        return $this->belongsTo('App\Http\Models\Compras\DetalleOrdenes','fk_id_detalle_orden_compra', 'id_orden_detalle');
    }
    public function facturaProveedor()
    {
        return $this->belongsTo('App\Http\Models\Compras\FacturasProveedores','fk_id_factura_proveedor','id_factura_proveedor');
    }
    public function estatus()
    {
        return $this->hasOne(EstatusAutorizaciones::class,'id_estatus','fk_id_estatus');
    }

}
