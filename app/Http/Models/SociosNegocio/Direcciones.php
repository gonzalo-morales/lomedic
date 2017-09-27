<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;
use DB;

class Direcciones extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sng_cat_direcciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_direccion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_socio_negocio','fk_id_pais','fk_id_estado','fk_id_municipio','fk_id_colonia','fk_id_tipo_direccion',
     'calle','num_exterior','num_interior','cp','activo'];
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
    // public $rules = [
    //     'fk_id_socio_negocio',
    //     'fk_id_pais'            => 'required',
    //     'fk_id_estado'          => 'required',
    //     'fk_id_municipio'       => 'required',
    //     'fk_id_colonia'         => 'required',
    //     'fk_id_tipo_direccion'  => 'required',
    //     'calle'                 => 'required',
    //     'num_exterior'          => 'required',
    //     'num_interior'          => 'required',
    //     'cp'                    => 'required'
    // ];

    /**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */


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

    public function socioNegocio(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\SociosNegocio','fk_id_socio_negocio');
    }
    public function colonia(){
        return $this->belongsTo('App\Http\Models\Administracion\Colonias','fk_id_colonia');
    }
    public function pais(){
        return $this->belongsTo('App\Http\Models\Administracion\Paises','fk_id_pais');
    }
    public function estado(){
        return $this->belongsTo('App\Http\Models\Administracion\estados','fk_id_estado');
    }
    public function municipio(){
        return $this->belongsTo('App\Http\Models\Administracion\municipios','fk_id_municipio');
    }
    public function tipoDireccion(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\TiposDireccion','fk_id_tipo_direccion');
    }

}
