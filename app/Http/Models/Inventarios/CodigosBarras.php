<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;
use Illuminate\Database\Eloquent\Model;

class CodigosBarras extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.inv_cat_codigos_barras';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_codigo_barra';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['codigo_barras','descripcion','fk_id_sku'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_codigo_barras' => 'ID',
        'codigo_barras' => 'Código de barras',
        'descripcion' => 'Producto',
        'fk_id_sku' => 'SKU'
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
        'codigo_barras' => 'required',
        'descripcion' => 'required',
        'fk_id_sku' => 'required'
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

    public function sku()
    {
        return $this->belongsTo('App\Http\Models\Inventarios\Skus','id_sku','fk_id_sku');
    }
}
