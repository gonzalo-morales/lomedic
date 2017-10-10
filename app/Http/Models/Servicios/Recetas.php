<?php

namespace App\Http\Models\Servicios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\Afiliaciones;
use App\Http\Models\Administracion\Diagnosticos;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Medicos;
use App\Http\Models\Administracion\Programas;

class Recetas extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rec_opr_recetas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_receta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id_sucursal', 'fecha','id_afiliacion','id_dependiente','id_medico','id_diagnostico',
        'id_programa','id_estatus_receta','id_area','nombre_paciente_no_afiliado','observaciones','id_usuario_creacion',
        'id_usuario_creacion','id_usuario_modificacion','fecha_modificacion','peso','altura','presion'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'peso' => 'between:0,999.99',
        'altura' => 'between:0,9.99',
        'presion1' => 'between:0,999.99',
        'presion2' => 'between:0,999.99',
        'id_diagnostico' => 'required|numeric',
        'id_afiliacion' => 'required_without:nombre_paciente_no_afiliado',
        'nombre_paciente_no_afiliado' => 'required_without:id_afiliacion'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'folio' => 'Folio',
        'unidad_medica'=>'Unidad medica',
        'tipo_servicio' => 'Tipo de servicio',
        'id_afiliacion' => 'N. de afiliacion',
        'nombre_completo_paciente' => 'Paciente',
        'fecha_formated' => 'Fecha Captura',
        'estatus_formated' => 'Estatus de la receta'
    ];

    public function getNombreCompletoMedicoAttribute()
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
        return $this->belongsTo(EstatusRecetas::class,'fk_id_estatus_receta','id_estatus_receta');
    }

    public function detalles()
    {
        return $this->belongsToMany(RecetasDetalle::class,'id_receta','fk_id_receta');
    }
}
