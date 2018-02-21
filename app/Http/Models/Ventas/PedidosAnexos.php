<?php

namespace App\Http\Models\Ventas;

use App\Http\Models\ModelCompany;

class PedidosAnexos extends ModelCompany
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'ven_det_pedidos_anexos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_anexo';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fk_id_proyecto', 'nombre', 'archivo'];

    public function pedido(){
        return $this->belongsTo(Pedidos::class,'fk_id_documento');
    }
}