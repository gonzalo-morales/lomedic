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
    protected $fillable = ['fk_id_proveedor','serie_factura','folio_factura','fecha_captura','fk_id_usuario_captura','fecha_revision','fk_id_usuario_revision','estatus','tipo'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_seguimiento_desviacion' => 'ID',
        'fk_id_proveedor'           => 'Proveedor',
        'serie_factura'             => 'Serie Factura',
        'folio_factura'             => 'Folio Factura',
    ];

    public function socios()
    {
        return $this->hasMany('App\Http\Models\SociosNegocio\SociosNegocio','fk_id_proveedor', 'id_socio_negocio');
    }
    public function usuarios()
    {
        return $this->hasOne(Usuarios::class,'fk_id_usuario_captura','id_usuario');
    }
    public function detallesSeguimientoDesviacion()
    {
        return $this->hasMany('App\Http\Models\Compras\DetalleSeguimientoDesviacion','fk_id_seguimiento_desviacion','id_seguimiento_desviacion');
    }
    // public function tipoDocumento()
    // {
    //     return $this->belongsTo('App\Http\Models\Administracion\TiposDocumentos','fk_id_tipo_documento','id_tipo_documento');
    // }
    // public function productos()
    // {
    //     return $this->hasMany('App\Http\Models\Inventarios\EntradaDetalle','fk_id_entrada_almacen', 'id_entreda_almacen');
    // }

}
