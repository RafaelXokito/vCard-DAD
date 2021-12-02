<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VCard;
use Illuminate\Auth\Access\HandlesAuthorization;

class VCardPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return false;
    }

    public function view(User $user, VCard $vcard)
    {
        return true;
        if ($user->id == $vcard->phone_number) {
            return true;
        }
        return false;
    }

    public function edit(User $user, VCard $vcard)
    {
        if ($user->id == $vcard->phone_number) {
            return true;
        }
        return false;
    }

    public function update(User $user, VCard $vcard)
    {
        if ($user->id == $vcard->phone_number) {
            return true;
        }
        return false;
    }

    public function delete(User $user, VCard $vcard)
    {
        if ($user->user_type == "A" || $user->id == $vcard->phone_number) {
            return true;
        }
        return false;
    }

    public function restore(User $user)
    {
        if ($user->user_type == "A") {
            return true;
        }
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

    public function updateMaxDebit(User $user)
    {
        return $user->user_type == "A";
    }
}
