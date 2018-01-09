<?php

namespace App\Http\Models\Ventas;

use App\Http\Models\Administracion\TiposRelacionesCfdi;
use App\Http\Models\ModelCompany;
use DB;

class CfdiRelacionesClientes extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fac_det_cfdi_relaciones_proveedores';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_relacion_cfdi_clientes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_documento',
        'fk_id_tipo_documento',
        'fk_id_tipo_relacion',
        'fk_id_documento_relacionado',
        'fk_id_tipo_documento_relacionado',
        'eliminar'
    ];

    function notaCreditoCliente()
    {
        return $this->belongsTo(NotasCreditoClientes::class,'fk_id_documento','id_nota_credito');
    }
    function factura()
    {
        return $this->belongsTo(FacturasClientes::class,'fk_id_documento','id_factura');
    }
    function tiporelacion()
    {
        return $this->hasOne(TiposRelacionesCfdi::class,'id_sat_tipo_relacion','fk_id_tipo_relacion');
    }
}
