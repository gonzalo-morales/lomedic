<?php

namespace App\Http\Models\SociosNegocio;

use App\Http\Models\ModelBase;
use App\Http\Models\Administracion\Bancos;

class CuentasSociosNegocio extends ModelBase
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'sng_det_cuentas_bancarias';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_cuenta';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['fk_id_banco','fk_id_socio_negocio','no_cuenta','no_sucursal','clave_interbancaria','activo'];

    /**
	 * Los atributos que seran visibles en index-datable
	 * @var array
	 */
	protected $fields = [];

	public function banco(){
        return $this->belongsTo(Bancos::class,'fk_id_banco');
    }
    
    public function socioNegocio(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\SociosNegocio','fk_id_socio_negocio');
    }
}