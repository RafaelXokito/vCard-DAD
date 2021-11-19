<?php

namespace App\Policies;

use App\Models\DefaultCategory;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DefaultCategoryPolicy
{
    use HandlesAuthorization;

    // If user is admin, authorization check always return true
    // Admin user is granted all previleges over "Disciplina" entity
    public function before($user, $ability)
    {
        return false;
    }

    public function viewAny(User $user)
    {
        return false;
    }

    public function view(User $user, DefaultCategory $defaultCategory)
    {
        return true;
    }

    public function create(User $user)
    {
        return false;
    }

    public function store(User $user)
    {
        return false;
    }

    public function edit(User $user)
    {
        return false;
    }

    public function update(User $user)
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
}
