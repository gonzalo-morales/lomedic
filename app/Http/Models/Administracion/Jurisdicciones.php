<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Jurisdicciones extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_jurisdicciones';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_jurisdiccion';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['jurisdiccion', 'fk_id_estado','activo'];

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
        'jurisdiccion' => 'required|regex:/^([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-])+((\s*)+([0-9a-zA-ZñÑáéíóúÁÉÍÓÚ_-]*)*)+$/',
    ];

    public function estado()
    {
        return $this->belongsTo('App\Http\Models\Administracion\Estados');
    }
}
