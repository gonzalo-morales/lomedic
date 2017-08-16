<?php

namespace App\Http\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class ModelBase extends Model
{

	/**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [];

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

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
