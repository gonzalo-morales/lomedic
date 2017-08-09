<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;

class Bancos extends Model
{
    // use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'gen_cat_bancos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_banco';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['razon_social', 'banco', 'rfc', 'nacional'];

    /**
     * Los atributos que seran visibles en index-datable
     * @var [type]
     */
    protected $fields = [
      'banco' => 'Banco',
      'razon_social' => 'Razón Social',
      'rfc' => 'RFC',
      'nacional' => 'Nacional'
    ];

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
        'razon_social' => 'required',
        'banco' => 'required',
    ];

    public function getFields()
    {
        return $this->fields;
    }

    public function numeroscuenta()
    {
        return $this->hasMany('App\Http\Models\Administracion\NumerosCuenta');
    }
}
