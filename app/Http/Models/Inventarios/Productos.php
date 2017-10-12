<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\GrupoProductos;
use App\Http\Models\Administracion\SubgrupoProductos;

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
    protected $fillable = ['sku','descripcion'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    protected $fields = [
        'id_sku' => 'ID',
        'sku' => 'SKU',
        'descripcion' => 'Descripcion',
        'presentacion' => 'Presentacion',
        'grupo.grupo' => 'Grupo',
        'subgrupo.subgrupo' => 'Subgrupo',
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
    
    public function grupo()
    {
        return $this->belongsTo(GrupoProductos::class,'fk_id_grupo','id_grupo');
    }
    
    public function subgrupo()
    {
        return $this->belongsTo(SubgrupoProductos::class,'fk_id_subgrupo','id_subgrupo');
    }
}
