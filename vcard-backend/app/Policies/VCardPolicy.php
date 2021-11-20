<?php

namespace App\Policies;

use App\Models\User;
use App\Models\VCard;
use Illuminate\Auth\Access\HandlesAuthorization;

class VCardPolicy
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
        return false;
    }

    public function view(User $user, User $vcard)
    {
        if ($user->vcard_ref->id == $vcard->phone_number) {
            return true;
        }
        return false;
    }

    public function create(User $user)
    {
        return true;
    }

    public function store(User $user)
    {
        return false;
    }

    public function edit(User $user, User $vcard)
    {
        if ($user->vcard_ref->id == $vcard->phone_number) {
            return true;
        }
        return false;
    }

    public function update(User $user, User $vcard)
    {
        if ($user->vcard_ref->id == $vcard->phone_number) {
            return true;
        }
        return false;
    }

    public function delete(User $user, User $vcard)
    {
        if ($user->vcard_ref->id == $vcard->phone_number) {
            return true;
        }
        return false;
    }

    public function restore(User $user)
    {
        return false;
    }

    public function accessCritial(User $user)
    {
        /*if ($user->user_type == 'A') {
            return true;
        }*/
        $vcard = $user->vcard_ref;
        if (!$vcard->custom_data) {
            return false;
        }
        $custom_data = $vcard->custom_data;
        $custom_data = json_decode((string) $custom_data, true);

        if ($custom_data["phonenumber_confirmed"] == "true")
            return true;
        return false;
    }

    public function forceDelete(User $user)
    {
        return false;
    }
}
