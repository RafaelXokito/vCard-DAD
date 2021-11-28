<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

    // If user is admin, authorization check always return true
    // Admin user is granted all previleges over "Disciplina" entity
    public function before($user, $ability)
    {
        //return false;
    }

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Transaction $transaction)
    {
        if ($transaction->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        return false;
    }

    public function viewPair(User $user)
    {
        if ($user->user_type == 'A') {
            return true;
        }
        return false;
    }

    public function store(User $user)
    {
        if ($user->user_type == 'V') {
            return true;
        }
        return false;
    }

    public function edit(User $user, Transaction $transaction)
    {
        if ($transaction->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        return false;
    }

    public function update(User $user, Transaction $transaction)
    {
        if ($transaction->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        return false;
    }
}
