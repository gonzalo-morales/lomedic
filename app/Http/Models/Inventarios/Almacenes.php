<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelBase;

class Almacenes extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.inv_cat_almacenes';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_almacen';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['codigo','nombre','fk_id_sucursal','virtual','fk_id_almacen','generar_inventario','longitud',
        'ancho','alto','altura','volumen','peso','activo'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'codigo' => 'Código',
        'nombre' => 'Nombre',
        'sucursal.nombre_sucursal' => 'Sucursal',
        'virtual' => 'Virtual',
        'activo' => 'Activo'
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
        'codigo' => 'required',
        'nombre' => 'required',
        'fk_id_sucursal' => 'required',
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

    public function sucursal()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal','id_sucursal');
    }

    public function almacen()
    {
        return $this->belongsTo('App\Http\Models\Inventarios\Almacenes','fk_id_almacen','id_almacen');
    }

}
