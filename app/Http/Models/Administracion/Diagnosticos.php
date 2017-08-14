<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Diagnosticos extends Model
{
	// use SoftDeletes;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'gen_cat_diagnosticos';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_diagnostico';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['clave_diagnostico', 'diagnostico', 'medicamento_sugerido',];

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
		'clave_diagnostico' => 'required',
		'diagnostico' => 'required',
		'medicamento_sugerido' => 'required',
	];

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [
		'clave_diagnostico' => 'Clave',
		'diagnostico' => 'Diagnostico',
		'medicamento_sugerido' => 'Medicamento Sugerido'
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
