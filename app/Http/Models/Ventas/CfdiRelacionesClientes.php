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
    protected $table = 'fac_det_cfdi_relaciones_clientes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_relacion_cfdi_cliente';

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

    function documento()
    {
    switch($this->fk_id_tipo_documento_relacionado)
        {
            case 4://Factura
                return $this->hasOne(FacturasClientes::class,'id_factura','fk_id_documento_relacionado');
                break;
            case 5://Crédito
                return $this->hasOne(NotasCreditoClientes::class,'id_nota_credito','fk_id_documento_relacionado');
                break;
            case 6://Cargo
                return $this->hasOne(NotasCargoClientes::class,'id_nota_cargo','fk_id_documento_relacionado');
                break;
        }
    }
    function tiporelacion()
    {
        return $this->hasOne(TiposRelacionesCfdi::class,'id_sat_tipo_relacion','fk_id_tipo_relacion');
    }
}
