<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Compras\Licitaciones;
use App\Http\Models\ModelCompany;
use DB;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Administracion\EstatusDocumentos;

class Proyectos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pry_cat_proyectos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_proyecto';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['proyecto','fk_id_cliente','fecha_inicio','fecha_terminacion','fk_id_clasificacion_proyecto',
        'fk_id_estatus','fk_id_localidad','num_evento','fk_id_tipo_evento','fk_id_dependencia','fk_id_subdependencia',
        'fk_id_sucursal','fk_id_caracter_evento','fk_id_forma_adjudicacion','fk_id_modalidad_entrega'];

    public $niceNames =[
        'fk_id_cliente'=>'cliente',
        'fecha_contrato' =>'fecha de contrato',
        'fecha_inicio_contrato' => 'fecha de inicio de contrato',
        'fecha_fin_contrato' => 'fecha de fin de contrato',
        'numero_contrato' => 'número de contrato',
        'numero_proyecto' => 'número de proyecto',
        'monto_adjudicado' => 'monto adjudicado',
        'fk_id_clasificacion_proyecto' => 'clasificación proyecto',
        'representante_legal' => 'representante legal',
        'numero_fianza' => 'número de fianza',
        'num_evento' => 'número de evento',
        'fk_id_tipo_evento' => 'tipo evento',
        'fk_id_dependencia' => 'dependencia',
        'fk_id_subdependencia' => 'subdependencia',
        'fk_id_sucursal' => 'sucursal',
        'fk_id_caracter_evento' => 'caracter evento',
        'fk_id_forma_adjudicacion' => 'forma_adjudicacion',
        'fk_id_modalidad_entrega' => 'modalidad_entrega'
    ];

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
        'proyecto' => 'required|max:50',
        'fk_id_cliente' => 'required',
        'fecha_inicio' => 'required',
        'fecha_terminacion' => 'required',
        'fk_id_clasificacion_proyecto'=>'required',
        'fk_id_estatus' => 'required',
        'fk_id_localidad' => 'required',
    ];

    public $fields = [
        'proyecto' => 'Proyecto',
        'cliente.nombre_comercial' => 'Cliente',
        'num_evento' => 'No. Evento',
        'fecha_inicio' => 'Fecha Inicio',
        'fecha_terminacion' => 'Fecha Terminacion',
        'estatus.estatus' => 'Estatus'
    ];

    function cliente()
    {
        return $this->belongsTo(SociosNegocio::class,'fk_id_cliente');
    }
    
    function estatus()
    {
        return $this->hasOne(EstatusDocumentos::class,'id_estatus','fk_id_estatus');
    }
    
    function tipoevento()
    {
        return $this->hasOne(TiposEventos::class,'id_tipo_evento','fk_id_tipo_evento');
    }
    
    function dependencia()
    {
        return $this->hasOne(Dependencias::class,'id_dependencia','fk_id_dependencia');
    }
    
    function subdependencia()
    {
        return $this->hasOne(Subdependencias::class,'id_subdependencia','fk_id_subdependencia');
    }

    function productos()
    {
        return $this->hasMany(ProyectosProductos::class,'fk_id_proyecto','id_proyecto');
    }
    
    public function contratos(){
        return $this->hasMany(ContratosProyectos::class,'fk_id_proyecto','id_proyecto');
    }
    
    public function anexos(){
        return $this->hasMany(AnexosProyectos::class,'fk_id_proyecto','id_proyecto');
    }

    public function licitacion()
    {
        return $this->hasOne(Licitaciones::class,'no_oficial','num_evento');
    }

    public function sucursal()
    {
        return $this->hasOne(Sucursales::class,'id_sucursal','fk_id_sucursal');
    }

    public function caracterevento()
    {
        return $this->hasOne(CaracterEventos::class,'id_caracter_evento','fk_id_caracter_evento');
    }

    public function formaadjudicacion()
    {
        return $this->hasOne(FormasAdjudicacion::class,'id_forma_adjudicacion','fk_id_forma_adjudicacion');
    }

    public function modalidadentrega()
    {
        return $this->hasOne(ModalidadesEntrega::class,'id_modalidad_entrega','fk_id_modalidad_entrega');
    }
}