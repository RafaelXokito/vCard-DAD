<?php

namespace App\Policies;

use App\Models\PaymentType;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentTypePolicy
{
    use HandlesAuthorization;

    public function before($user, $ability)
    {
        //
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user)
    {
        return $user->user_type == 'A';
    }

    public function store(User $user)
    {
        return $user->user_type == 'A';
    }

    public function edit(User $user)
    {
        return $user->user_type == 'A';
    }

    public function update(User $user)
    {
        return $user->user_type == 'A';
    }

    public function delete(User $user)
    {
        return $user->user_type == 'A';
    }

    public function restore(User $user)
    {
        return $user->user_type == 'A';
    }

    public function forceDelete(User $user)
    {
        return $user->user_type == 'A';
    }
}
