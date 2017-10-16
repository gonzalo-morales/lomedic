<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;

class TiposSocioNegocio extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sng_cat_tipos_socio_negocio';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_socio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tipo_socio', 'activo'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The validation rules
     * @var array
     */
    // public $rules = [
    //     'tipo_socio'     => 'required',
    //     'activo'         => 'required',
    // ];

    public function getTable(){
	    return $this->table;
    }
    public function sng(){
        return $this->belongsToMany(SociosNegocio::class,'sng_det_tipo_socio_negocio','fk_id_tipo_socio','fk_id_socio_negocio');
    }

}
