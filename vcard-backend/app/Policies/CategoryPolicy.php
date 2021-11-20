<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    // If user is admin, authorization check always return true
    // Admin user is granted all previleges over "Disciplina" entity
    public function before($user, $ability)
    {
        if ($user->user_type == 'A') {
            return true;
        }
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Category $category)
    {
        if ($category->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        return false;
    }

    public function create(User $user)
    {
        return $user->user_type == 'V';
    }

    public function store(User $user)
    {
        return false;
    }

    public function edit(User $user, Category $category)
    {
        if ($category->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        return false;
    }

    public function update(User $user, Category $category)
    {
        if ($category->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        return false;
    }

    public function delete(User $user, Category $category)
    {
        if ($category->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        return false;
    }

    public function restore(User $user)
    {
        return false;
    }

    public function forceDelete(User $user, Category $category)
    {
        if ($category->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        return false;
    }
}
