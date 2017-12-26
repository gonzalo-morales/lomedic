<?php

namespace App\Http\Models\Liciplus;

use App\Http\Models\ModelCompany;

class Contratos extends ModelCompany
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'liciplus_contrato';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'no_oficial';

    public function licitacion(){
        return $this->belongsTo(Licitaciones::class,'no_oficial','no_oficial');
    }
}