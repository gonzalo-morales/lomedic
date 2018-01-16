<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;

class MovimientoAlmacenDetalle extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_det_movimiento_almacen';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_movimiento';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_stock',
        'fk_id_movimiento',
        'fk_id_sku',
        'fk_id_upc',
        'lote',
        'fecha_caducidad',
        'fk_id_ubicacion',
        'stock',
        'activo'
    ];

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

    public function tipo()
    {
        return $this->hasOne(MovimientoAlmacen::class,'id_movimiento','fk_id_movimiento');
    }
    public function sku()
    {
        return $this->hasOne(Productos::class, 'id_sku', 'fk_id_sku');
    }  
    public function upcs()
    {
        return $this->hasOne(Upcs::class,'id_upc','fk_id_upc');
    }
    public function ubicacion()
    {
        return $this->hasOne(Ubicaciones::class,'id_ubicacion','fk_id_ubicacion');
    }
    public function stock()
    {
        return $this->belongsTo(Stock::class, 'fk_id_stock', 'fk_id_stock');
    }

}
