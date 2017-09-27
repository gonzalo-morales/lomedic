<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;

class EmpresaSocioNegocio extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sng_empresa_socio_negocio';

    /**
     * The primary key of the table
     * @var string
     */
    // protected $primaryKey = '';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_empresa', 'fk_id_socio_negocio', 'activo'];

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
    //     'fk_id_empresa'          => 'required',
    //     'fk_id_socio_negocio'    => 'required',
    // ];

    public function getTable(){
	    return $this->table;
    }

    public function empresa(){
        return $this->belongsTo('App\Http\Models\Administracion\Empresas','fk_id_empresa');
    }
    public function sociosNegocio(){
        return $this->belongsTo('App\Http\Models\SociosNegocio\SociosNegocio','fk_id_socio_negocio');
    }

}
