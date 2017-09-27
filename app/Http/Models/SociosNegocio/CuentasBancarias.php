<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;
use DB;

class CuentasBancarias extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sng_cat_cuentas_bancarias';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_cuenta';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_banco','fk_id_socio_negocio','no_cuenta','activo'];
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
        'fk_id_banco'           => 'required',
        'fk_id_socio_negocio'   => 'required',
        'no_cuenta'             => 'required',
    ];

    /**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	// protected $fields = [
	//   'id_proveedor'   => 'Proveedor',
    //   'nombre'         => 'Nombre',
    //   'rfc'            => 'RFC',
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

    public function banco(){
        return $this->belongsTo('App\Http\Models\Admnistracion\Bancos','fk_id_banco');
    }
    public function socioNegocio(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\SociosNegocio','fk_id_socio_negocio');
    }

}
