<?php

namespace App\Http\Models\Finanzas;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;
use App\Http\Models\Administracion\ConceptosViaje;
use App\Http\Models\Administracion\Impuestos;

class DetalleGastosRelacionViajes extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fin_det_opr_gastos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_detalle_gastos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'folio',
        'fk_id_tipo',
        'subtotal',
        'fk_id_impuesto',
        'total',
        'activo',

    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var array
     */
    // protected $fields = [
    //     'folio',
    //     'fk_id_tipo',
    //     'subtotal',
    //     'fk_id_impuesto',
    //     'total'
    // ];

    // /**
    //  * Indicates if the model should be timestamped.
    //  *
    //  * @var bool
    //  */
    // public $timestamps = false;

    // /**
    //  * The validation rules
    //  * @var array
    //  */
    // public $rules = [
    //     'folio' => 'required',
    //     'fk_id_tipo' => 'required',
    //     'subtotal' => 'required',
    //     'fk_id_impuesto' => 'required',
    //     'total' => 'required'

    // ];

    public function tipo()
    {
        return $this->hasOne(GastosViaje::class,'id_concepto','fk_id_tipo');
    }
    public function impuestos()
    {
        return $this->hasOne(Impuestos::class,'id_impuesto','fk_id_impuesto');
    }
}
