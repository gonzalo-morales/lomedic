<?php

namespace App\Http\Models\Administracion;

use DB;
use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_bancos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_banco';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['razon_social', 'banco', 'rfc', 'nacional'];

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
        'razon_social' => 'required',
        'banco' => 'required',
    ];

    public function getTable(){
	    return $this->table;
    }

    public function numeroscuenta()
    {
        return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
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
