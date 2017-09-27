<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;

class TiposDireccion extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sng_cat_tipos_direccion';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_tipo_direccion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['tipo_direccion', 'activo'];

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
    //     'tipo_direccion' => 'required',
    //     'activo'         => 'required',
    // ];

    public function getTable(){
	    return $this->table;
    }

}
