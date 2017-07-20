<?php

namespace App\Http\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuarios extends Authenticatable
{
	use Notifiable;

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'ges_cat_usuarios';

	/**
	 * The primary key of the table
	 * @var string
	 */
	protected $primaryKey = 'id_usuario';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name', 'email', 'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password', 'remember_token',
	];

    public function mails(){
        return $this->hasMany('app\Http\Models\Correos');
    }

    public function profiles(){
        return $this->belongsToMany('app\Http\Models\Perfiles','ges_det_usuario_perfil','fk_id_perfil','fk_id_usuario');
    }

    public function branches(){
        return $this->belongsToMany('app\Http\Models\Sucursales','ges_det_usuario_sucursal','fk_id_usuario','fk_id_sucursal');
    }
}
