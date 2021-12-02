<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdministratorPolicy
{
    use HandlesAuthorization;

    public function store(User $user)
    {
        return $user->user_type == "A";
    }
}
