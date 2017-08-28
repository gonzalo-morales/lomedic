<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;
use Illuminate\Support\HtmlString;

class Jurisdicciones extends ModelBase
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

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'jurisdiccion' => 'Jurisdicción',
        'estado.estado' => 'Estado',
        'activo_span' => 'Activo',
    ];

    public function getActivoFormatedAttribute()
    {
        return $this->activo ? 'Activo' : 'Inactivo';
    }

    public function getActivoSpanAttribute()
    {
        return new HtmlString("<span class=" . ($this->activo ? 'toast_success' : 'toast_error') . ">&nbsp;$this->activo_formated&nbsp;</span>");
    }

    /**
     * Obtenemos entidad federativa relacionada
     * @return \Illuminate\Database\Eloquent\Concerns\belongsTo
     */
    public function estado()
    {
        return $this->belongsTo(Estados::class, 'fk_id_estado', 'id_estado');
    }
}
