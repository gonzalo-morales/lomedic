<?php

namespace App\Http\Models\SociosNegocio;

use Illuminate\Database\Eloquent\Model;

class RamosSocioNegocio extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sng_cat_ramos_socio';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_ramo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ramo', 'activo'];

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
    //     'ramo' => 'required',
    //     'activo' => 'required',
    // ];

    public function getTable(){
	    return $this->table;
    }

}
