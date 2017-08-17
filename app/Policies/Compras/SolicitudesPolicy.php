<?php

namespace App\Policies\Compras;

use App\Http\Models\Administracion\Usuarios;
use Illuminate\Auth\Access\HandlesAuthorization;

class SolicitudesPolicy
{
	use HandlesAuthorization;

	/**
	 * Determine whether the user can view the post.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Post  $post
	 * @return mixed
	 */
	public function view(Usuarios $usuario)
	{
		$permisos = $usuario->permisos()->pluck('descripcion');
		return $permisos->contains(currentRouteAction('view'));
	}

	/**
	 * Determine whether the user can create posts.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(Usuarios $usuario)
	{
		$permisos = $usuario->permisos()->pluck('descripcion');
		return $permisos->contains(currentRouteAction('create'));
	}

	/**
	 * Determine whether the user can update the post.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Post  $post
	 * @return mixed
	 */
	public function update(Usuarios $usuario)
	{
		$permisos = $usuario->permisos()->pluck('descripcion');
		return $permisos->contains(currentRouteAction('update'));
	}

	/**
	 * Determine whether the user can delete the post.
	 *
	 * @param  \App\User  $user
	 * @param  \App\Post  $post
	 * @return mixed
	 */
	public function delete(Usuarios $usuario)
	{
		$permisos = $usuario->permisos()->pluck('descripcion');
		return $permisos->contains(currentRouteAction('delete'));
	}
}