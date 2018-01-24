<?php

namespace App\Http\Models\Compras;

use App\Http\Models\Administracion\TiposDocumentos;
use App\Http\Models\Administracion\Usuarios;
use App\Http\Models\ModelCompany;
use App\Http\Models\Compras\CondicionesAutorizacion;
use DB;

class Autorizaciones extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_det_autorizaciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_autorizacion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_documento','fk_id_tipo_documento','fk_id_condicion','fk_id_usuario_autoriza','fk_id_estatus','fecha_creacion','fecha_autorizacion','observaciones','activo'];

    public $niceNames =[
        'fk_id_estatus'=>'estatus autorización'
    ];

    protected $dataColumns = [];
    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
     protected $fields = [
         'id_autorizacion'=>'No. Autorizacion',
         'condicion.nombre' => 'Condicion',
         'tipodocumento.nombre_documento' => 'Tipo Documento',
         'fk_id_documento' => 'No. Documento',
         'fecha_creacion' => 'Fecha',
         'estatus.estatus'=>'Estatus'
     ];

    function setEstatusAttribute(){
        return $this->estatus->id_estatus;
    }
    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'observaciones'=>'requiredif:fk_id_estatus,3'
    ];

    public function documento()
    {
        return $this->hasOne('App\Http\Models\Compras\Ordenes','id_orden','fk_id_documento');
    }

    public function solicitudpago()
    {
        return $this->belongsTo(SolicitudesPagos::class,'id_solicitud_pago','fk_id_documento');
    }

    public function tipodocumento()
    {
        return $this->hasOne(TiposDocumentos::class,'id_tipo_documento','fk_id_tipo_documento');
    }

    public function usuario()
    {
        return $this->hasOne(Usuarios::class,'id_usuario','fk_id_usuario_autoriza');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusAutorizaciones::class,'id_estatus','fk_id_estatus');
    }

    public function condicion()
    {
        return $this->hasOne(CondicionesAutorizacion::class,'id_condicion','fk_id_condicion');
    }
}