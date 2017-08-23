<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class ModelBase extends Model
{
	/**
	 * Los atributos que seran visibles en index-datable
	 * @var null|array
	 */
	protected $fields = null;
	
	protected $appends = ['activo_span', 'activo_text'];
	
	public function getActivoTextAttribute()
	{
	    return $this->activo ? 'Activo' : 'Inactivo';
	}
	
	public function getActivoSpanAttribute()
	{
	    $format = new HtmlString("<span class=" . ($this->activo ? 'toast_success' : 'toast_error') . ">&nbsp;$this->activo_text&nbsp;</span>");
	    if (request()->ajax()) {
	        return $format->toHtml();
	    }
	    return $format;
	}

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Obtenemos atributos para smart-datatable
	 * @return array
	 */
	public function getFields()
	{
		if (!$this->fields) {
			throw new \Exception('Undefined $fields in ' . class_basename($this) . ' model.');
		}
		return $this->fields;
	}

	/**
	 * Obtenemos defaults de modelo
	 */
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
