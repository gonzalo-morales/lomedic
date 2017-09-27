<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;
use DB;

class CorreosContacto extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sng_det_correos_contacto';

    /**
     * The primary key of the table
     * @var string
     */
    // protected $primaryKey = 'id_correo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_contacto','correo','activo'];
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
        'fk_id_contacto'   => 'required',
        'correo'           => 'required',
    ];

    /**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	// protected $fields = [
	//   'correo'          => 'Correo',
    //   'fk_id_proveedor' => 'Proveedor',
	// ];

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

    public function contacto(){
       return $this->belongsTo('App\Http\Models\SociosNegocio\ContactosSoociosNegocio', 'fk_id_contacto');
    }
}
