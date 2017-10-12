<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;
use DB;

class SociosNegocio extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.gen_cat_socios_negocio';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_socio_negocio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = ['fk_id_tipo_entrega','razon_social','rfc','nombre_corto','telefono','sitio_web','monto_credito','dias_credito','monto_minimo_facturacion','fecha_modificacion','activo'];
    protected $fillable = ['fk_id_tipo_socio','fk_id_forma_pago','fk_id_tipo_entrega','fk_id_sucursal_entrega','fk_id_usuario_modificacion',
    'razon_social','rfc','nombre_corto','telefono','sitio_web','monto_credito','dias_credito','monto_minimo_facturacion','fecha_modificacion',
    'ejecutivo_venta','fk_id_ramo','fk_id_pais_origen','fk_id_moneda','activo'];
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    const UPDATED_AT = 'fecha_modificacion';
    public $timestamps = false;
    public function setUpdatedAtAttribute($value) {
        $this->attributes['fecha_modificacion'] = \Carbon\Carbon::now();
    }

    /**
     * The validation rules
     * @var array
     */
    // public $rules = [
    //     'razon_social' => 'required',
    //     'rfc' => 'required',
    // ];

    /**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	// protected $fields = [
	//   'id_proveedor'   => 'Proveedor',
    //   'nombre'         => 'Nombre',
    //   'rfc'            => 'RFC',
	// ];

	// public function getFields()
	// {
	// 	return $this->fields;
	// }

	// public function ColumnDefaultValues()
	// {
	// 	$schema = config('database.connections.'.$this->getConnection()->getName().'.schema');
    //
	// 	$data = DB::table('information_schema.columns')
	// 		->select('column_name', 'data_type', DB::Raw("replace(replace(column_default, concat('::',data_type), ''),'''','') as column_default"))
	// 		->whereRaw('column_default is not null')
	// 		->whereRaw("column_default not ilike '%nextval%'")
	// 		->where('table_name','=',$this->table)
	// 		->where('table_schema','=',$schema)
	// 		->where('table_catalog','=',$this->getConnection()->getDatabaseName())->get();
    //
	//    foreach ($data as $value) {
	// 	   $data->{$value->column_name} = $value->data_type == 'boolean' ? $value->column_default == 'true' : $value->column_default;
	//    }
    //
	// 	return $data;
	// }

    public function formaPago(){
        return $this->belongsTo('App\Http\Models\Administracion\FormasPago','fk_id_forma_pago');
    }
    public function tipoEntrega(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\TiposEntrega','fk_id_tipo_entrega');
    }
    public function sucursal(){
        return $this->belongsTo('App\Http\Models\Administracion\Sucursales','fk_id_sucursal_entrega');
    }
    public function usuario(){
        return $this->belongsTo('App\Http\Models\Administracion\Usuarios','fk_id_usuario_modificacion');
    }
    public function ramo(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\RamosSocioNegocio','fk_id_ramo');
    }
    public function tipoSocio(){
        // return $this->hasManyThrough('App\Http\Models\SociosNegocio\TipoToSocioNegocio','App\Http\Models\SociosNegocio\TiposSocioNegocio','id_tipo_socio','fk_id_tipo_socio');
        // App\Group::with('comments')->get()
        // return $this->belongsToMany('App\Http\Models\SociosNegocio\TiposSocioNegocio','sng_det_tipo_socio_negocio','fk_id_tipo_socio','fk_id_socio_negocio');
        return $this->belongsToMany('App\Http\Models\SociosNegocio\TiposSocioNegocio','sng_det_tipo_socio_negocio','fk_id_socio_negocio','fk_id_tipo_socio');
    }

}
