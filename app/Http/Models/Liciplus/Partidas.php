<?php

namespace App\Http\Models\Liciplus;

use App\Http\Models\ModelCompany;

class Partidas extends ModelCompany
{
    /**
     * The table associated with the model.
     * @var string
     */
    protected $table = 'liciplus_partida';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'partida';

    public function licitacion(){
        return $this->belongsTo(Licitaciones::class,'no_oficial','no_oficial');
    }
}