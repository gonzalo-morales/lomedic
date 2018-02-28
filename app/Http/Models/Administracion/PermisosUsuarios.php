<?php
/**
 * Created by PhpStorm.
 * User: ihernandezt
 * Date: 9/10/2017
 * Time: 09:47
 */

namespace App\Http\Models\Administracion;

use App\Http\Models\ModelBase;


class PermisosUsuarios extends ModelBase
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'maestro.adm_det_permisos_usuarios';

    /**
     * The primary key of the table
     * @var string
     */
    protected $primaryKey = 'fk_id_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['fk_id_usuario','fk_id_modulo_accion'];


    public $rules = [
        'fk_id_usuario' => 'required',
        'fk_id_modulo_accion' => 'required',
    ];


}
