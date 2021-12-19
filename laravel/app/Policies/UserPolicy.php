<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

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
        return $user->id == $model->id;
    }

    public function update(User $user, User $model)
    {
        return $user->id == $model->id;
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
        return false;
    }

    public function store(User $user)
    {
        return false;
    }

    public function delete(User $user)
    {
        return $user->user_type == "A";
    }

    public function restore(User $user)
    {
        return false;
    }

    public function forceDelete(User $user)
    {
        return false;
    }

    public function accessCritial(User $user)
    {
        if ($user->user_type == 'A') {
            return true;
        }
        $vcard = $user->vcard_ref;
        if (!$vcard->custom_data) {
            return false;
        }
        $custom_data = json_decode((string) $vcard->custom_data, true);

        if ($custom_data["phonenumber_confirmed"] == "true")
            return true;
        return false;
    }
}
