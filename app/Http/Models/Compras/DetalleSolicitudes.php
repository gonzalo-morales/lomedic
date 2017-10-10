<?php

namespace App\Http\Models\Compras;

use App\Http\Models\ModelCompany;
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
    protected $primaryKey = 'id_solicitud_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_solicitud','fk_id_sku','fk_id_upc','fk_id_proveedor','cantidad',
        'fk_id_unidad_medida','fk_id_impuesto','precio_unitario','total','fk_id_proyecto','fecha_necesario'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

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
}
