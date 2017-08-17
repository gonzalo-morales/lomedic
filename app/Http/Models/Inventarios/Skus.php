<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;

class Skus extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.inv_cat_skus';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_sku';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['sku'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_sku' => 'ID',
        'sku' => 'SKU',
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
        'sku' => 'required',
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

    public function codigos_barras()
    {
        return $this->hasMany('App\Http\Models\Inventarios\CodigosBarras','id_codigo_barras');
    }
}
