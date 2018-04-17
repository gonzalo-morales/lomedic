<?php

namespace App\Policies;

use App\Http\Models\Administracion\Usuarios;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Http\Models\Ventas\NotasCreditoClientes;

class PolicyBase
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function view($usuario,$entity = null)
    {
        return true;
        return \Auth::User()->checkAuthorization(currentRouteAction('view'));
    }

    /**
     * Determine whether the user can create posts.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create($usuario,$entity = null)
    {
        return true;
        return \Auth::User()->checkAuthorization(currentRouteAction('create'));
    }

    /**
     * Determine whether the user can update the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function update($user,$entity = null)
    {
        if(in_array('fk_id_estatus',$entity->getlistColumns())){
            if($entity->fk_id_estatus == 3)
                return false;
        }

        if(in_array('fk_id_estatus_cfdi',$entity->getlistColumns())){
            if($entity->fk_id_estatus_cfdi != 1)
                return false;
        }

        return true;
        return \Auth::User()->checkAuthorization(currentRouteAction('update'));
    }

    /**
     * Determine whether the user can delete the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function delete($usuario,$entity = null,$idOrIds = [])
    {
        return true;
        return \Auth::User()->checkAuthorization(currentRouteAction('delete'));
    }

    /**
     * Determine whether the user can export the post.
     *
     * @param  \App\User  $user
     * @param  \App\Post  $post
     * @return mixed
     */
    public function export($usuario,$entity = null)
    {
        return true;
        return \Auth::User()->checkAuthorization(currentRouteAction('export'));
    }
}