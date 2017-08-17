<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\ModelCompany;
use DB;

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
    protected $fillable = ['proyecto'];

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
        'proyecto' => 'required'
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
}
