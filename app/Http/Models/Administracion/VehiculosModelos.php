<?php

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;

class VehiculosModelos extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_vehiculos_modelos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_modelo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['modelo', 'fk_id_marca','activo'];

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'modelo' => 'required|regex:/^([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-])+((\s*)+([0-9a-zA-ZÃ±Ã‘Ã¡Ã©Ã­Ã³ÃºÃ�Ã‰Ã�Ã“Ãš_-]*)*)+$/',
        'fk_id_marca' => 'required|numeric'
    ];

    /**
     * Los atributos que seran visibles en index-datable
     * @var null|array
     */
    protected $fields = [
        'modelo' => 'Modelo',
        'marca.marca' => 'Marca',
        'activo_span' => 'Estatus'
    ];

    public function marca()
    {
        return $this->belongsTo(VehiculosMarcas::class, 'fk_id_marca', 'id_marca');
    }
}