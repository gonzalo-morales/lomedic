<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\Administracion\FormasPago;
use App\Http\Models\Administracion\Monedas;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\TiposRelacionesCfdi;
use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;

class CfdiRelacionesProveedores extends ModelCompany
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
    protected $primaryKey = 'id_relacion_cfdi_proveedores';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_documento','fk_id_tipo_documento','fk_id_tipo_relacion','fk_id_documento_relacionado','fk_id_tipo_documento_relacionado'];

    function notaCreditoProveedor()
    {
        return $this->hasOne(NotasCreditoProveedor::class,'id_documento','fk_id_documento_relacionado');
    }
    function factura()
    {
        return $this->hasOne(FacturasProveedores::class,'id_documento','fk_id_documento_relacionado');
    }
    function tiporelacion()
    {
        return $this->hasOne(TiposRelacionesCfdi::class,'id_sat_tipo_relacion','fk_id_tipo_relacion');
    }
}
