<?php

namespace App\Http\Models\Compras;

use App\Http\Models\ModelCompany;
use DB;
use Illuminate\Database\Eloquent\Model;

class Solicitudes extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'com_opr_solicitudes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_solicitud';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_solicitante','fk_id_sucursal','fk_id_departamento','fecha_creacion','fecha_necesidad',
        'fecha_cancelacion','motivo_cancelacion','fk_id_estatus_solicitud'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_solicitud' => 'Número Solicitud',
        'fk_id_solicitante' => 'Solicitante',
        'fk_id_sucursal' => 'Sucursal',
        'fecha_creacion' => 'Fecha de solicitud',
        'fecha_necesidad' => 'Fecha necesidad',
        'fk_id_estatus_solicitud' => 'Estatus'
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
        'fk_id_solicitante' => 'required',
        'fk_id_sucursal' => 'required',
        'fecha_necesidad' => 'required'
    ];

    public function getFields()
    {
        return $this->fields;
    }

    public function ColumnDefaultValues()
    {
        $schema = config('database.connections.'.$this->getConnection()->getName().'.schema');

        $data = DB::table('information_schema.columns')
            ->select('column_name', 'data_type', DB::Raw("replace(replace(column_default, concat('::',data_type), ''),'''','') as column_default"))
            ->whereRaw('column_default is not null')
            ->whereRaw("column_default not ilike '%nextval%'")
            ->where('table_name','=',$this->table)
            ->where('table_schema','=',$schema)
            ->where('table_catalog','=',$this->getConnection()->getDatabaseName())->get();

        foreach ($data as $value) {
            $data->{$value->column_name} = $value->data_type == 'boolean' ? $value->column_default == 'true' : $value->column_default;
        }

        return $data;
    }

    public function empleado()
    {
        return $this->belongsTo('App\Http\Models\RecursosHumanos\Empleados','fk_id_solicitante','id_empleado');
    }

    public function estatus()
    {
        return $this->hasOne('App\Http\Models\Compras\EstatusSolicitudes','id_estatus','fk_id_estatus_solicitud');
    }

    public function detalleSolicitudes()
    {
        return $this->hasMany('App\Http\Models\Compras\DetalleSolicitudes','fk_id_solicitud', 'id_solicitud');
    }
}
