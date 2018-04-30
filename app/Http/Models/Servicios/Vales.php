<?php

namespace App\Http\Models\Servicios;

use App\Http\Models\Administracion\EstatusDocumentos;
use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\Afiliaciones;
use App\Http\Models\Administracion\Diagnosticos;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Medicos;
use App\Http\Models\Administracion\Programas;
use App\Http\Models\Proyectos\Proyectos;

class Vales extends ModelCompany
{

    protected $table = 'rec_opr_vales';

    protected $primaryKey = 'id_vale';

    protected $fillable = [
        'observaciones',
        'fk_id_usuario_captura',
        'fk_id_usuario_modificacion',
        'fecha_modificacion',
        'fk_id_receta',
        'fecha_surtido',
        'cancelado',
        'fecha_cancelado',
        'motivo_cancelado',
        'fk_id_usuario_cancelado',
        'eliminado',
        'fk_id_estatus_surtido',
        'patente',
        'fk_id_sucursal'
    ];

    public $niceNames =[
        'fk_id_sucursal' =>'sucursal',
    ];

    public $rules = [
        'fk_id_sucursal' => 'required',
    ];

    protected $fields = [
        'id_vale' => '#',
        'fk_id_receta'=>'Receta',
        'receta.nombre_completo_paciente'=>'Paciente',
        'fecha_surtido'=>'Fecha de surtido',
        'sucursal.sucursal'=>'Sucursal',
        'estatus_documento_span'=>'Estatus'
    ];

    public function getNombreMedicoAttribute()
    {
        return $this->medico->nombre.' '.$this->medico->paterno.' '.$this->medico->materno;
    }

    public function getNombreCompletoPacienteAttribute()
    {
        if($this->id_afiliacion != '' && $this->id_afiliacion != null){
            return $this->afiliacion->paterno.' '.$this->afiliacion->materno.' '.$this->afiliacion->nombre;
        }else{
            return $this->nombre_paciente_no_afiliado;
        }
    }

    public function getTipoServicioAttribute(){
        if($this->id_afiliacion != '' || $this->id_afiliacion != null){
            return 'Afiliado';
        }else{
            return 'Externo';
        }
    }

    public function getUnidadMedicaAttribute(){
        return $this->sucursal->sucursal;
    }

    public function getFechaFormatedAttribute(){
        return date("d-m-Y",strtotime($this->fecha));
    }

    public function getEstatusFormatedAttribute(){

        return $this->estatus->estatus_receta;
    }

    public function afiliacion()
    {
        return $this->belongsTo(Afiliaciones::class,'fk_id_afiliacion','id_afiliacion');
    }

    public function diagnostico()
    {
        return $this->belongsTo(Diagnosticos::class,'fk_id_diagnostico','id_diagnostico');
    }

    public function sucursal()
    {
        return $this->belongsTo(Sucursales::class,'fk_id_sucursal','id_sucursal');
    }

    public function medico()
    {
        return $this->belongsTo(Medicos::class,'fk_id_medico','id_medico');
    }

    public function programa()
    {
        return $this->belongsTo(Programas::class,'fk_id_programa','id_programa');
    }

    public function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus_receta');
    }
    public function proyecto()
    {
        return $this->hasMany(Proyectos::class,'fk_id_proyecto','id_proyecto');
    }
    public function detalles()
    {
        return $this->hasMany( ValesDetalle::class,'fk_id_vale','id_vale');
    }
    public function receta()
    {
        return $this->hasOne( Recetas::class,'id_receta','fk_id_receta');
    }

}
