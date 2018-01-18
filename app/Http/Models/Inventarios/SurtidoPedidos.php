<?php

namespace App\Http\Models\Inventarios;

use App\Http\Models\ModelCompany;

class Stock extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'inv_opr_surtido_pedidos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_surtido';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'fk_id_pedido_detalle',
        'fk_id_sku',
        'fk_id_upc',
        'cantidad_solicitada_salida',
        'cantidad_escaneada',
        'falta_surtir',
        'activo'
    ];

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


}
