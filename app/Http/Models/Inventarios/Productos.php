<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\GrupoProductos;
use App\Http\Models\Administracion\SubgrupoProductos;
use App\Http\Models\Administracion\SeriesSkus;
use App\Http\Models\Administracion\UnidadesMedidas;

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
        'fk_id_serie_sku','sku','nombre_comercial','descripcion','presentacion','fk_id_unidad_medida','articulo_venta','articulo_compra','articulo_inventario','maneja_lote','fk_id_subgrupo'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_sku' => 'ID',
        'sku' => 'SKU',
        'descripcion' => 'Descripcion',
        'presentacion' => 'Presentacion',
        'unidadmedida.nombre' => 'Unidad de Medida',
        'subgrupo.grupo.grupo' => 'Grupo',
        'subgrupo.subgrupo' => 'Subgrupo',
        'activo_span' => 'Estatus',
    ];

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
        'sku' => 'required',
        'descripcion' => 'required'
    ];

    public function upcs()
    {
        return $this->belongsToMany(Upcs::class,'inv_det_sku_upc','fk_id_sku','fk_id_upc');
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
}
