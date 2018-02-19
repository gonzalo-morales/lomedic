<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;
use App\Http\Models\Administracion\Sucursales;
use App\Http\Models\Inventarios\MovimientoAlmacenDetalle;

class Stock extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_opr_stock';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_stock';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_sku',
        'fk_id_upc',
        'fk_id_ubicacion',
        'fk_id_almacen',
        'lote',
        'fecha_caducidad',
        'stock',
        'costo',
        'apartados',
        'fk_id_documento',
        'activo'
    ];

    /*
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    // protected $fields = [];

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
     * Obtenemos ubicacion relacionado
     * @return @belongsTo
     */
    public function ubicacion()
    {
        return $this->belongsTo(Ubicaciones::class, 'fk_id_ubicacion', 'id_ubicacion');
    }

    /**
     * Obtenemos almacen relacionado
     * @return @belongsTo
     */
    public function almacen()
    {
        return $this->belongsTo(Almacenes::class, 'fk_id_almacen', 'id_almacen');
    }

    /**
     * Obtenemos stock relacionado
     * @return @belongsTo
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientoAlmacenDetalle::class, 'fk_id_sku', 'id_sku');
    }
}
