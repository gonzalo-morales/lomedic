<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 9/10/2017
 * Time: 17:56
 */

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;


class PerfilesUsuarios extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.adm_det_perfiles_usuarios';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'fk_id_perfil';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_perfil','fk_id_usuario'];


    public $rules = [
        'fk_id_perfil' => 'required',
        'fk_id_usuario' => 'required',
    ];

}
