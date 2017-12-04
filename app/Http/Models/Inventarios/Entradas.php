<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\Compras\Ordenes;
use App\Http\Models\ModelCompany;
use DB;

class Entradas extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_opr_entrada_almacen';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_entrada_almacen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_tipo_documento','numero_documento','referencia_documento',
        'fecha_entrada','fk_id_estatus_entrada'];

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
        'id_entrada_almacen' => 'Número Entrada',
        'fk_id_tipo_documento' => 'Tipo docuemnto',
        'numero_documento' => 'Numero de documento',
        'referencia_documento' => 'Referencia del documentos',
        'fecha_entrada' => 'Fecha de entrada',
        'fk_id_estatus_entrada' => 'Estatus de la entrada'
    ];

    public function detalleEntrada()
    {
        return $this->hasMany('App\Http\Models\Inventarios\EntradaDetalle','fk_id_entrada_almacen', 'id_entreda_almacen');
    }
//
//    public function datosEntrada($id,$tipo_documento)
//    {
//        switch ($tipo_documento)
//        {
//            case 1:
//                break;
//            case 2:
//                breack;
//            case 3:
//                $documento = Ordenes::where('id_orden',$id)->first();
//                break;
//        }
//
//        return $documento;
//    }

    public function sucursales()
    {
        return $this->hasOne('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }
    public function proveedor()
    {
        return $this->hasOne('App\Http\Models\SociosNegocio\SociosNegocio','id_socio_negocio','fk_id_socio_negocio');
    }
    public function tipoDocumento()
    {
        return $this->belongsTo('App\Http\Models\Administracion\TiposDocumentos','fk_id_tipo_documento','id_tipo_documento');
    }
    public function productos()
    {
        return $this->hasMany('App\Http\Models\Inventarios\EntradaDetalle','fk_id_entrada_almacen', 'id_entreda_almacen');
    }

//    public function detalleEntrada()
//    {
//        return $this->hasMany('App\Http\Models\Inventarios\EntradaDetalle','fk_id_entrada_almacen', 'id_entreda_almacen');
//    }

}
