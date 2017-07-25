<?php

namespace App\Http\Models\Administracion;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Correos extends Model
{
    const CREATED_AT = 'fecha_crea';
    const UPDATED_AT = 'fecha_actualiza';


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ges_det_correos';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_correo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['correo', 'fk_id_empresa', 'fk_id_usuario','activo'];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The validation rules
     * @var array
     */
    public $rules = [
        'correo' => 'required|email',
    ];


    /*Information for selects*/
    public function user()
    {
        return $this->belongsTo('app\Http\Models\Administracion\Usuarios');
    }
}
