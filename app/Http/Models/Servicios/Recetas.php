<?php

namespace App\Http\Models\Servicios;

use App\Http\Models\Administracion\Dependencias;
use App\Http\Models\Administracion\Parentescos;
use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\Afiliaciones;
use App\Http\Models\Administracion\Diagnosticos;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Administracion\Medicos;
use App\Http\Models\Administracion\Programas;
use App\Http\Models\Proyectos\Proyectos;
use DB;

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
    protected $fillable = [
        'folio',
        'fk_id_sucursal',
        'fecha',
        'fk_id_afiliacion',
        'fk_id_dependiente',
        'fk_id_medico',
        'fk_id_diagnostico',
        'fk_id_programa',
        'fk_id_estatus_receta',
        'fk_id_area',
        'nombre_paciente_no_afiliado',
        'observaciones',
        'fecha_modificacion',
        'peso',
        'altura',
        'presion_sistolica',
        'presion_diastolica',
        'fk_id_proyecto',
        'fk_id_parentesco',
        'fk_id_afiliado',
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */

    public $niceNames =[
        'folio' => 'Folio',
        'fk_id_sucursal' => 'Sucursal',
        'fecha' => 'Fecha',
        'fk_id_afiliacion' => 'No. Afiliado',
        'fk_id_dependiente' => 'Dependiente',
        'fk_id_medico' => 'Medico',
        'fk_id_diagnostico' => 'Diagnostico',
        'fk_id_programa' => 'Programa',
        'fk_id_estatus_receta' => 'Estatus de la receta',
        'fk_id_area' => 'Area',
        'nombre_paciente_no_afiliado' => 'Nombre paciente no afiliado',
        'observaciones' => 'Observaciones',
        'fecha_modificacion' => 'Fecha de modificaion',
        'peso' => 'Peso',
        'altura' => 'Altura',
        'presion_sistolica' => 'Presion sistolica',
        'presion_diastolica' => 'Presion diastolica',
        'fk_id_proyecto' => 'Proyecto',
        'fk_id_parentesco' => 'Parentesco',
        'fk_id_afiliado' => 'No. Afiliado',
    ];

    protected $fields = [
        'id_receta'=>'#',
        'folio' => 'Folio',
        'unidad_medica'=>'Unidad medica',
        'tipo_servicio' => 'Tipo de servicio',
        'numero_afiliado' => 'N. de afiliacion',
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
        if($this->fk_id_afiliado != '' && $this->fk_id_afiliado != null){
            return $this->afiliacion->paterno.' '.$this->afiliacion->materno.' '.$this->afiliacion->nombre;
        }else{
            return $this->nombre_paciente_no_afiliado;
        }
    }

    public function getNumeroAfiliadoAttribute()
    {
        return $this->afiliacion['id_afiliacion'];
    }

    public function getTipoServicioAttribute(){
        if($this->fk_id_afiliado != '' || $this->fk_id_afiliado != null){
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
        return $this->belongsTo(Afiliaciones::class,'fk_id_afiliado','id_afiliado');
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
    public function proyecto()
    {
        return $this->hasMany(Proyectos::class,'fk_id_proyecto','id_proyecto');
    }

    public function titular($fk_id_afiliado)
    {
        $titular = Afiliaciones::where('id_afiliado',$fk_id_afiliado)->pluck('id_dependiente');
        return Afiliaciones::select(DB::raw("CONCAT(nombre,' ',paterno,' ',materno) AS nombre"))->where('id_afiliacion', $titular)->pluck('nombre')->first();
    }
    public function dependiente($fk_id_afiliado)
    {
        return Afiliaciones::select(DB::raw("CONCAT(nombre,' ',paterno,' ',materno) AS nombre,genero,fecha_nacimiento,fk_id_parentesco"))->where('id_afiliado',$fk_id_afiliado)->first();
    }
    public function parentesco()
    {
        return $this->hasOne(Parentescos::class,'id_parentesco','fk_id_parentesco');
    }
    public function detalles()
    {
        return $this->hasMany(RecetasDetalle::class,'fk_id_receta','id_receta');
    }

}
