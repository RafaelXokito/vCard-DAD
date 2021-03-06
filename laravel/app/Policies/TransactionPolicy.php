<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TransactionPolicy
{
    use HandlesAuthorization;

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
        // if ($user->user_type == 'A') {
        //     return true;
        // }
        return false;
    }

    public function store(User $user)
    {
        return true;
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

    public function viewPaymentType(User $user, Transaction $transaction)
    {
        if ($transaction->vcard == $user->vcard_ref->phone_number) {
            return true;
        }
        if ($user->user_type == 'A') {
            return true;
        }
        return false;
    }
}
