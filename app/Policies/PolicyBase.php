<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

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
    public function view()
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
    public function create()
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
    public function update()
    {
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
    public function delete()
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
    public function export()
    {
        return true;
        return \Auth::User()->checkAuthorization(currentRouteAction('export'));
    }
}