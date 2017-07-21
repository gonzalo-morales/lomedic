<?php

namespace App\Http\Parents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parents extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ges_det_parent';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'fk_id_modulo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_modulo', 'fk_id_parent'];

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
    ];

    /**
     * Las empresas que relacionan al modulo.
     */

    public function  modules()
    {
        return $this->belongsToMany('App\Http\Models\Modulos','ges_det_parents','fk_id_modulo','fk_id_parent');
    }
}
