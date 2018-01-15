<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Compras\Ordenes;
use App\Http\Models\ModelCompany;
use DB;

class SeguimientoDesviacion extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_opr_seguimiento_desviaciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_seguimiento_desviacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_proveedor','fk_id_localidad','serie_factura','folio_factura','fecha_captura','fecha_revision'];

//    public $niceNames =[
//        'fk_id_socio_negocio'=>'proveedor'
//    ];
//
//    protected $dataColumns = [
//        'fk_id_estatus_orden'
//    ];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_seguimiento_desviacion' => 'ID',
        'fk_id_proveedor'           => 'Proveedor',
        'fk_id_localidad'           => 'Localidad'
    ];

    public function socios()
    {
        return $this->hasMany('App\Http\Models\SociosNegocio\SociosNegocio','fk_id_proveedor', 'id_socio_negocio');
    }
    // public function sucursales()
    // {
    //     return $this->hasOne('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    // }
    // public function proveedor()
    // {
    //     return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_socio_negocio');
    // }
    // public function tipoDocumento()
    // {
    //     return $this->belongsTo('App\Http\Models\Administracion\TiposDocumentos','fk_id_tipo_documento','id_tipo_documento');
    // }
    // public function productos()
    // {
    //     return $this->hasMany('App\Http\Models\Inventarios\EntradaDetalle','fk_id_entrada_almacen', 'id_entreda_almacen');
    // }

}
