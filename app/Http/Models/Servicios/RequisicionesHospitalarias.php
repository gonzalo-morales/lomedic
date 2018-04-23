<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 04/09/2017
 * Time: 12:21
 */

namespace App\Http\Models\Servicios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Servicios\RequisicionesHospitalariasDetalle;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Servicios\EstatusRequisicionesHospitalarias;
use App\Http\Models\Administracion\Usuarios;


class RequisicionesHospitalarias extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'req_opr_requisiciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_requisicion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folio',
        'fk_id_sucursal',
        'fecha_captura',
        'fecha_requerimiento',
        'fk_id_usuario_captura',
        'fk_id_usuario_modifica',
        'fecha_modifica',
        'fk_id_estatus_requisicion_hospitalaria',
        'fk_id_solicitante',
        'fk_id_programa',
    ];

//    public $niceNames =[
//        'fk_id_sucursal'=>'sucursal',
////        'fk_id_solicitante'=>'solicitantes',
////        'fecha_contrato' =>'fecha de contrato',
////        'fecha_inicio_contrato' => 'fecha de inicio de contrato',
////        'fecha_fin_contrato' => 'fecha de fin de contrato',
////        'numero_contrato' => 'número de contrato',
////        'numero_proyecto' => 'número de proyecto',
////        'monto_adjudicado' => 'monto adjudicado',
////        'fk_id_clasificacion_proyecto' => 'clasificación proyecto',
////        'representante_legal' => 'representante legal',
////        'numero_fianza' => 'número de fianza',
////        'num_evento' => 'número de evento',
////        'fk_id_tipo_evento' => 'tipo evento',
////        'fk_id_dependencia' => 'dependencia',
////        'fk_id_subdependencia' => 'subdependencia',
////        'fk_id_sucursal' => 'sucursal',
////        'fk_id_caracter_evento' => 'caracter evento',
////        'fk_id_forma_adjudicacion' => 'forma_adjudicacion'
//    ];

    /**
     * Los atributos que seran ]visibles en index-datable
     * @var array
     */
    protected $fields = [
        'sucursal.sucursal' => 'Sucursal',
        'folio' => 'Folio',
        'solicitantes.nombre_corto' => 'Solicitante',
        'captura.nombre_corto' => 'Captura',
        'fecha_captura' => 'Fecha captura',
        'fecha_requerimiento' => 'Fecha requerimiento',
        'activo_span' => 'Estatus',
    ];

    public function estatus()
    {
        return $this->hasOne(EstatusRequisicionesHospitalarias::class,'id_estatus_requisicion_hospitalaria','fk_id_estatus_requisicion_hospitalaria');
    }

    public function sucursal()
    {
        return $this->hasOne(Sucursales::class,'id_sucursal','fk_id_sucursal');
    }

    public function solicitantes()
    {
        return $this->hasOne(Usuarios::class,'id_usuario','fk_id_solicitante');
    }
    public function captura()
    {
        return $this->hasOne(Usuarios::class,'id_usuario','fk_id_usuario_captura');
    }

    public function getSolicitanteAttribute()
    {
        $paterno = !empty($this->solicitantes->paterno) ? $this->solicitantes->paterno : '';
        $materno = !empty($this->solicitantes->materno) ? $this->solicitantes->materno : '';
        $nombre  = !empty($this->solicitantes->nombre) ? $this->solicitantes->nombre : '';

        return "$paterno $materno $nombre";
    }

    public function getIsucursalAttribute()
    {
        return !empty($this->sucursal->sucursal) ? $this->sucursal->sucursal: '';
    }

    public function getIestatusAttribute()
    {
        return !empty($this->estatus->estatus) ? $this->estatus->estatus : '';
    }

    public function detalles()
    {
        return $this->hasMany(RequisicionesHospitalariasDetalle::class,'fk_id_requisicion','id_requisicion');
    }

}
