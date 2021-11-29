<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    // If user is admin, authorization check always return true
    // Admin user is granted all previleges over "Disciplina" entity
    public function before($user, $ability)
    {
        //
    }

    public function viewAny(User $user)
    {
        return $user->user_type == "A";
    }

    public function view(User $user, User $model)
    {
        return $user->user_type == "A" || $user->id == $model->id;
    }

    public function edit(User $user, User $model)
    {
        return $user->user_type == "A" || $user->id == $model->id;
    }

    public function update(User $user, User $model)
    {
        return $user->user_type == "A" || $user->id == $model->id;
    }

    public function updatePassword(User $user, User $model)
    {
        return $user->id == $model->id;
    }

    public function updateCode(User $user, User $model)
    {
        return $user->id == $model->id;
    }


    public function create(User $user)
    {
        return true;
    }

    public function store(User $user)
    {
        return false;
    }

    public function delete(User $user)
    {
        return false;
    }

    public function restore(User $user)
    {
        return false;
    }

    public function forceDelete(User $user)
    {
        return false;
    }

    public function block(User $user)
    {
        return $user->user_type == "A";
    }
}
