<?php

namespace App\Http\Models\Compras;

use App\Http\Models\ModelCompany;
use App\Http\Models\Ventas\Pedidos;
use DB;

class DetalleSolicitudes extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_det_solicitudes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_documento_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_sku','fk_id_upc','fk_id_proveedor','cantidad',
        'fk_id_unidad_medida','fk_id_impuesto','precio_unitario','importe','fk_id_proyecto','fecha_necesario',
        'fk_id_documento','fk_id_documento_base','fk_id_tipo_documento_base','fk_id_linea','impuesto_total'];

    public function getFields()
    {
        return $this->fields;
    }

    public function sku()
    {
        return $this->hasOne('App\Http\Models\Inventarios\Productos','id_sku','fk_id_sku');
    }

    public function upc()
    {
        return $this->hasOne('App\Http\Models\Inventarios\Upcs','id_upc','fk_id_upc');
    }

    public function unidad_medida()
    {
        return $this->hasOne('App\Http\Models\Administracion\Unidadesmedidas','id_unidad_medida','fk_id_unidad_medida');
    }

    public function impuesto()
    {
        return $this->hasOne('App\Http\Models\Administracion\Impuestos','id_impuesto','fk_id_impuesto');
    }

    public function proyecto()
    {
        return $this->hasOne('App\Http\Models\Proyectos\Proyectos','id_proyecto','fk_id_proyecto');
    }

    public function solicitudes()
    {
        return $this->belongsTo('App\Http\Models\Compras\Solicitudes','fk_id_solicitud','id_solicitud');
    }

    public function proveedor()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_proveedor');
    }

    public function documento()
    {
        switch ($this->fk_id_tipo_documento_base){
            case 4:
                return $this->belongsTo(FacturasClientes::class,'fk_id_documento_base','');
                break;
            case 8://Pedido de cliente
                   return $this->belongsTo(Pedidos::class,'fk_id_documento_base','id_documento');
                break;
        }
    }
}
