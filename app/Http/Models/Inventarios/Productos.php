<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\SubgrupoProductos;
use App\Http\Models\Administracion\SeriesSkus;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Compras\DetalleOrdenes;
use App\Http\Models\Administracion\Presentaciones;
use App\Http\Models\Administracion\Especificaciones;

class Productos extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_cat_skus';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_sku';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "sku",
        "fk_id_cbn",
        "fk_id_serie_sku",
        "fk_id_forma_farmaceutica",
        "fk_id_presentaciones",
        "descripcion_corta",
        "articulo_venta",
        "articulo_compra",
        "articulo_inventario",
        "maneja_lote",
        "fk_id_subgrupo",
        "fk_id_impuesto",
        "fk_id_familia",
        "activo",
        "activo_desde",
        "activo_hasta",
        "necesario",
        "maximo",
        "minimo",
        "fk_id_metodo_valoracion",
        "punto_reorden",
        "fk_id_intervalo",
        "minima_periodo",
        "tiempo_lead",
        "dias_tolerancia",
        "descripcion",
        "descripcion_cenefas",
        "descripcion_ticket",
        "descripcion_rack",
        "descripcion_cbn",
        'material_curacion'
    ];

    public $rules = [
        'fk_id_serie_sku' => 'required',
        'sku' => 'required',
        'fk_id_forma_farmaceutica' => 'required',
        'fk_id_presentaciones' => 'required',
        'descripcion_corta' => 'required|max:200',
        'fk_id_impuesto' => 'required',
        'fk_id_subgrupo' => 'required'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_sku' => 'ID',
        'sku' => 'SKU',
        'descripcion_corta' => 'Descripcion',
        'presentacion_text' => 'Presentacion',
        'subgrupo.grupo.grupo' => 'Grupo',
        'subgrupo.subgrupo' => 'Subgrupo',
        'activo_span' => 'Estatus'
    ];

    public function getSkuDescripcionAttribute() {
        return $this->sku . ' - ' . $this->descripcion_corta;
    }

    /**
     * Obtenemos upcs relacionados a skus
     * @return @belongsToMany
     */
    public function upcs()
    {
        return $this->belongsToMany(Upcs::class,$this->schema.'.inv_det_sku_upc','fk_id_sku','fk_id_upc')->withPivot('cantidad');
    }

    public function getPresentacionTextAttribute()
    {
        if(!empty($this->fk_id_presentaciones))
            return ucwords(strtolower($this->presentacion->cantidad.' '.$this->presentacion->unidad->nombre));
        else
            return 'N/A';
    }  
    public function serie()
    {
        return $this->belongsTo(SeriesSkus::class,'fk_id_serie_sku','id_serie_sku');
    }
    
    public function subgrupo()
    {
        return $this->belongsTo(SubgrupoProductos::class,'fk_id_subgrupo','id_subgrupo');
    }
    
    public function clave_cliente_productos()
    {
        return $this->hasOne(ClaveClienteProductos::class,'fk_id_sku','id_sku');
    }
    
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'id_sku', 'fk_id_sku');
    }
    
    public function proveedores()
    {
        return $this->belongsToMany(SociosNegocio::class,getSchema().'.sng_det_productos','fk_id_sku','fk_id_socio_negocio','id_sku','id_socio_negocio')->withPivot('tiempo_entrega');
    }
    
    public function cbn()
    {
        return $this->belongsto(Cbn::class,'id_cbn','fk_id_cbn');
    }

    public function presentacion()
    {
        return $this->hasOne(Presentaciones::class,'id_presentacion','fk_id_presentaciones');
    }

    public function presentaciones()
    {
        return $this->hasMany(DetallePresentacionesSku::class, 'fk_id_sku','id_sku');
    }

    public function setUpcsAttribute($upc)
    {
        $this->attributes['upcs'] = $upc;
    }

    public function especificaciones()
    {
        return $this->belongsToMany(Especificaciones::class,'inv_det_especificaciones_producto','fk_id_sku','fk_id_especificacion','id_sku','id_especificacion');
    }

}