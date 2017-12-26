<?php

namespace App\Http\Models\Liciplus;

use App\Http\Models\ModelCompany;
use App\Http\Models\Proyectos\Proyectos;
use DB;

class Licitaciones extends ModelCompany
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'liciplus_licitacion';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'no_oficial';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'no_oficial','tipo_evento', 'dependencia', 'subdependencia', 'unidad', 'modalidad_entrega', 'caracter_evento',
        'forma_adjudicacion', 'pena_convencional', 'tipo_pena_convencional', 'tope_pena_convencional',
        'tipo_tope_pena_convencional', 'plazo_entrega'
    ];

    public function proyecto()
    {
        return $this->belongsTo(Proyectos::class,'num_evento','no_oficial');
    }
}
