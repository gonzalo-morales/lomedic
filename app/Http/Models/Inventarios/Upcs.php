<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelBase;
use App\Http\Models\Administracion\PresentacionVenta;
use App\Http\Models\Administracion\Laboratorios;
use App\Http\Models\Administracion\Paises;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Inventarios\DetalleIndicaciones;
use App\Http\Models\Inventarios\DetallePresentaciones;

class Upcs extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_cat_upcs';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_upc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'upc',
        'registro_sanitario',
        'nombre_comercial',
        'descripcion',
        'marca',
        'peso',
        'longitud',
        'fk_id_pais_origen',
        'fk_id_laboratorio',
        'ancho',
        'altura',
        'descontinuado',
        'activo',
        'fk_id_presentacion_venta',
        'fk_id_tipo_producto',
        'fk_id_forma_farmaceutica',
        'fk_id_via_administracion',
        'costo_base',
        'fk_id_moneda',
        'fk_id_tipo_familia',
        'fk_id_subgrupo_producto',
        'fk_id_presentaciones'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'upc' => 'UPC',
        'descripcion' => 'Descripcion',
        'nombre_comercial' => 'Nombre Comercial',
        'registro_sanitario' => 'Registro Sanitario',
        'marca' => 'Marca',
        'presentacion.presentacion_venta' => 'Presentacion',
        'laboratorio.laboratorio' => 'Laboratorio',
        'pais.pais' => 'Pais Origen',
    ];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'upc' => 'max:50|required',
        'descripcion' => 'max:200|required',
        'nombre_comercial' => 'max:150',
        'registro_sanitario' => 'max:50|required',
        'marca' => 'max:150|required',
        'fk_id_laboratorio' => 'required',
        'fk_id_pais_origen' => 'required',
        'fk_id_presentacion_venta' => 'required',
        'fk_id_tipo_producto' => 'required',
        'fk_id_forma_farmaceutica' => 'required',
        'fk_id_via_administracion' => 'required',
        'costo_base' => 'required',
        'fk_id_moneda' => 'required',
        'fk_id_tipo_familia' => 'required',
        'fk_id_subgrupo_producto' => 'required',
        'fk_id_presentaciones' => 'required'
    ];

    protected $unique = ['upc'];

    public function presentacion()
    {
        return $this->belongsTo(PresentacionVenta::class,'fk_id_presentacion_venta','id_presentacion_venta');
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorios::class,'fk_id_laboratorio','id_laboratorio');
    }

    public function pais()
    {
        return $this->belongsTo(Paises::class,'fk_id_pais_origen','id_pais');
    }

    public function skus()
    {
        return $this->belongsToMany(Productos::class,getSchema(request()->company).'.inv_det_sku_upc','fk_id_upc','fk_id_sku','id_upc','id_sku')->withPivot('fk_id_upc','fk_id_sku','cantidad');
    }

    public function clavesclientes()
    {
        return $this->belongsToMany(ClaveClienteProductos::class,'inv_det_upc_clave_cliente','fk_id_upc','fk_id_clave_cliente','id_upc','id_clave_cliente_producto');
//        return $this->hasMany(ClaveClienteProductos::class,'fk_id_upc','id_upc');
    }

    public function clientes()
    {
        return $this->hasManyThrough(SociosNegocio::class,ClaveClienteProductos::class,'fk_id_upc','id_socio_negocio','id_upc','fk_id_cliente');
    }

    public function presentaciones()
    {
        return $this->hasMany(DetallePresentaciones::class, 'fk_id_upc','id_upc');
    }

    public function indicaciones()
    {
        return $this->hasMany(DetalleIndicaciones::class, 'fk_id_upc','id_upc');
    }
}
