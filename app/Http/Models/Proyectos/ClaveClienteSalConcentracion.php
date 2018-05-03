<?php

namespace App\Http\Models\Proyectos;

use App\Http\Models\Administracion\Presentaciones;
use App\Http\Models\Administracion\Sales;
use App\Http\Models\ModelCompany;

class ClaveClienteSalConcentracion extends ModelCompany
{
    protected $table = 'pry_det_clave_cliente_sal_concentracion';

    protected $primaryKey = 'id_detalle';

    protected $fillable = [
      'fk_id_clave_cliente',
      'fk_id_concentracion',
      'fk_id_sal',
    ];

    public function clavecliente()
    {
        return $this->hasOne(ClaveClienteProductos::class,'id_clave_cliente_producto','fk_id_clave_cliente_producto');
    }

    public function concentracion()
    {
        return $this->hasOne(Presentaciones::class,'id_presentacion','fk_id_concentracion');
    }

    public function sal()
    {
        return $this->hasOne(Sales::class,'id_sal','fk_id_sal');
    }
}