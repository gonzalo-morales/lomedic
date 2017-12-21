<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;

class SalidasDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_det_salidas';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_salida', 'fk_id_sku', 'fk_id_upc', 'fk_id_almacen', 'cantidad_solicitada', 'cantidad_surtida', 'cantidad_pendiente', 'eliminar'];

    /*
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    // protected $fields = [];

    /**
     * Atributos de carga optimizada
     * @var array
     */
    // protected $eagerLoaders = [];

    /**
     * The validation rules
     * @var array
     */
    // public $rules = [];

    /**
     * Nice names to validator
     * @var array
     */
    // public $niceNames = [];

    /**
     * Obtenemos sku relacionado
     * @return @belongsTo
     */
    public function sku()
    {
        return $this->belongsTo(Productos::class, 'fk_id_sku', 'id_sku');
    }

    /**
     * Obtenemos upc relacionado
     * @return @belongsTo
     */
    public function upc()
    {
        return $this->belongsTo(Upcs::class, 'fk_id_upc', 'id_upc');
    }

    /**
     * Obtenemos almacen relacionado
     * @return @belongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(Almacenes::class, 'fk_id_almacen', 'id_almacen');
    }


}
