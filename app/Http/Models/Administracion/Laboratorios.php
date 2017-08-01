<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Laboratorios extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_laboratorios';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_laboratorio';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['laboratorio','activo'];

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

        'laboratorio' => 'required',

    ];

    public function usuario()
    {
        $this->hasOne('app\Http\Models\Administracion\Usuarios');
    }

    public function empresa()
    {
        $this->$this->hasOne('app\Http\Models\Administracion\Empresas');
    }
}
