<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 3/1/2018
 * Time: 11:19
 */

namespace App\Http\Models\Inventarios;

use App\Http\Controllers\Inventarios\SurtidoRequisicionHospitalariaController;
use App\Http\Models\Administracion\Areas;
use App\Http\Models\Inventarios\Productos;
use App\Http\Models\Inventarios\SurtidoRequisicionHospitalaria;
use App\Http\Models\ModelCompany;
use App\Http\Models\Proyectos\ClaveClienteProductos;

//use App\Http\Models\Administracion\Sucursales;
//use App\Http\Models\Servicios\EstatusRequisicionesHospitalarias;
//use App\Http\Models\Administracion\Usuarios;


class SurtidoRecetaDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rec_det_surtido_receta';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_receta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_surtido_receta',
        'fk_id_clave_cliente_producto',
        'cantidad_solicitada',
        'cantidad_surtida',
        'eliminar'
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
//        'id_sucursal' => 'required',
//        'fecha_requerido' => 'required',
//        'id_solicitante' => 'required',
    ];

    protected $eagerLoaders = ['sucursal','estatus'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
//        'folio' => '#',
//        'fk_id_sucursal' => 'Sucursal',
//        'solicitante' => 'Solicitante',
//        'fecha' => 'Fecha captura',
//        'fecha_requerido' => 'Fecha requerimiento',
//        'fk_id_estatus_requisicion' => 'Estatus',
    ];

//    public function estatus()
//    {
//        return $this->hasOne(EstatusRequisicionesHospitalarias::class,'id_estatus_requisicion_hospitalaria','fk_id_estatus_requisicion_hospitalaria');
//    }
//
//    public function sucursal()
//    {
//        return $this->hasOne(Sucursales::class,'id_sucursal','id_sucursal');
//    }
//
//    public function area()
//    {
//        return $this->hasOne(Areas::class,'id_area','fk_id_area');
//    }
    public function claveClienteProducto()
    {
        return $this->hasOne(ClaveClienteProductos::class,'id_clave_cliente_producto','fk_id_clave_cliente_producto');
    }


//    public function producto()
//    {
//        return $this->hasOne(Productos::class,'id_sku','fk_id_sku');
//    }
////
//    public function solicitantes()
//    {
//        return $this->hasOne(Usuarios::class,'id_usuario','id_solicitante');
//    }
//
//    public function getSolicitanteAttribute()
//    {
//        $paterno = !empty($this->solicitantes->paterno) ? $this->solicitantes->paterno : '';
//        $materno = !empty($this->solicitantes->materno) ? $this->solicitantes->materno : '';
//        $nombre  = !empty($this->solicitantes->nombre) ? $this->solicitantes->nombre : '';
//
//        return "$paterno $materno $nombre";
//    }
//
//    public function getIsucursalAttribute()
//    {
//        return !empty($this->sucursal->sucursal) ? $this->sucursal->sucursal: '';
//    }
//
//    public function getIestatusAttribute()
//    {
//        return !empty($this->estatus->estatus) ? $this->estatus->estatus : '';
//    }

}
