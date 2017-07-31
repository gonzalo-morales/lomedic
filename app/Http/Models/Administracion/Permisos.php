<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permisos extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ges_cat_permisos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_permiso';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['descripcion'];

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
    public $rules = [
        'descripcion' => 'required',
    ];

    /**
     * Obtenemos los modulos relacionados a permiso
     * @return array
     */
    public function modulos()
    {
        return $this->belongsToMany('App\Http\Models\Administracion\modulos', 'ges_det_permisos_modulos', 'fk_id_permiso', 'fk_id_modulo');
    }

}
