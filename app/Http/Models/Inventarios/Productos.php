<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\SubgrupoProductos;
use App\Http\Models\Administracion\SeriesSkus;
use App\Http\Models\Administracion\UnidadesMedidas;
use App\Http\Models\Proyectos\ClaveClienteProductos;
use App\Http\Models\SociosNegocio\SociosNegocio;
use App\Http\Models\Compras\DetalleOrdenes;

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
        "activo",
        "descripcion_corta",
        "descripcion",
        "presentacion",
        "fk_id_unidad_medida",
        "articulo_venta",
        "articulo_compra",
        "articulo_inventario",
        "maneja_lote",
        "fk_id_subgrupo",
        "fk_id_serie_sku",
        "fk_id_impuesto",
        "fk_id_familia",
        "activo_desde",
        "activo_hasta",
        "fk_id_unidad_medida_venta",
        "fk_id_presentacion_venta",
        "fk_id_proveedor",
        "fk_id_unidad_medida_compra",
        "necesario",
        "maximo",
        "minimo",
        "punto_reorden",
        "fk_id_metodo_valoracion",
        "fk_id_intervalo",
        "minima_periodo",
        "tiempo_lead",
        "dias_tolerancia",
        "fk_id_clave_medida"
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_sku' => 'ID',
        'sku' => 'SKU',
        'descripcion_corta' => 'Descripcion',
        'presentacion' => 'Presentacion',
        'unidadmedida.nombre' => 'Unidad de Medida',
        'subgrupo.grupo.grupo' => 'Grupo',
        'subgrupo.subgrupo' => 'Subgrupo',
        'activo_span' => 'Estatus',
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

    public function serie()
    {
        return $this->belongsTo(SeriesSkus::class,'fk_id_serie_sku','id_serie_sku');
    }

    public function unidadmedida()
    {
        return $this->belongsTo(UnidadesMedidas::class,'fk_id_unidad_medida','id_unidad_medida');
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
}