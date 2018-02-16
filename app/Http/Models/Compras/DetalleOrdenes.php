<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Inventarios\EntradaDetalle;
use App\Http\Models\Inventarios\Entradas;
use App\Http\Models\ModelCompany;
use DB;

class DetalleOrdenes extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_det_ordenes';

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
    protected $fillable = ['fk_id_documento','fk_id_sku','fk_id_upc','fk_id_cliente','cantidad',
        'fk_id_impuesto','precio_unitario','total','fk_id_proyecto','fecha_necesario','fk_id_solicitud',
        'descuento_detalle','cerrado','fk_id_tipo_documento','fk_id_documento','fk_id_linea'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'fk_id_solicitud' => 'required',
        'fk_id_sku' => 'required',
        'fk_id_upc' => 'required',
        'fk_id_proveedor' => 'required',
        'cantidad' => 'required',
        'fk_id_unidad_medida' => 'required',
        'fk_id_impuesto' => 'required',
        'precio_unitario' => 'required',
        'total' => 'required'
    ];

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

    public function impuesto()
    {
        return $this->hasOne('App\Http\Models\Administracion\Impuestos','id_impuesto','fk_id_impuesto');
    }

    public function proyecto()
    {
        return $this->hasOne('App\Http\Models\Proyectos\Proyectos','id_proyecto','fk_id_proyecto');
    }

    public function orden()
    {
        return $this->belongsTo('App\Http\Models\Compras\Ordenes','fk_id_documento','id_documento');
    }

    public function cliente()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_cliente');
    }

    public function solicitud()
    {
        return $this->hasOne('App\Http\Models\Compras\Solicitudes','id_solicitud','fk_id_solicitud');
    }
    public function entradaDetalle()
    {
        return $this->hasOne('App\Http\Models\Inventarios\EntradaDetalle','fk_id_detalle_documento','id_documento_detalle');
    }
    public function sumatoriaCantidad($fk_id_documento,$numero_documento,$fk_id_sku,$fk_id_upc,$fk_id_detalle_documento)
    {

        if($fk_id_upc === null)
        {
            $fk_id_upc = 'null';
        }
        $entrada = Entradas::join('inv_det_entrada_almacen','inv_opr_entrada_almacen.id_entrada_almacen','=','inv_det_entrada_almacen.fk_id_entrada_almacen')
            ->where('inv_opr_entrada_almacen.fk_id_tipo_documento',$fk_id_documento)
            ->where('inv_opr_entrada_almacen.numero_documento',$numero_documento)
            ->where('inv_det_entrada_almacen.fk_id_sku',$fk_id_sku)
            ->where('inv_det_entrada_almacen.fk_id_upc',$fk_id_upc)
            ->where('inv_det_entrada_almacen.fk_id_detalle_documento',$fk_id_detalle_documento)
            ->sum('inv_det_entrada_almacen.cantidad_surtida');

        return $entrada;
    }

}
