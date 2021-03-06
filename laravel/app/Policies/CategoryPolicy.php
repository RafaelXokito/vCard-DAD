<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        if ($user->user_type == 'A') {
            return false;
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

    public function store(User $user)
    {
        return $user->user_type == 'V';
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
