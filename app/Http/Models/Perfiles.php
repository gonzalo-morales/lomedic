<?php
namespace App\Http\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Perfiles extends Model
{
    const CREATED_AT = 'fecha_crea';
    const UPDATED_AT = 'fecha_actualiza';


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ges_cat_perfiles';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'id_perfil';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['nombre_perfil', 'descripcion', 'activo','fk_id_usuario_crea'];

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
        'nombre_perfil' => 'required|unique:ges_cat_perfiles,nombre_perfil',
    ];

    public function usuarios(){
        return $this->belongsToMany('app\Http\Models\Usuarios','ges_det_usuario_perfil','fk_id_perfil','fk_id_usuario');
    }

    public function modulos()
    {
        return $this->hasMany('app\Http\Models\Modulos');
    }

}
