<?php

namespace App\Policies\Administracion;

use App\Http\Models\Administracion\Usuarios;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Route;


class BancosPolicy
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

		dump ( Route::currentRouteAction() );

		dump( ' GATE HAS ' );
		return false;


	}

	/**
	 * Determine whether the user can create posts.
	 *
	 * @param  \App\User  $user
	 * @return mixed
	 */
	public function create(Usuarios $usuario)
	{
		//
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
		//
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
		//
	}
}
