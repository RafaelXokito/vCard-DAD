<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use App\Models\VCard;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public static $format = "default";
    public function toArray($request)
    {
        switch (UserResource::$format) {
            case 'detailedVCard':
                return [
                    'id' => $this->id,
                    'username' => $this->username,
                    'name' => $this->name,
                    'email' => $this->email,
                    'photo_url' => $this->photo_url != null ? "/storage/fotos/" . $this->photo_url : "/storage/fotos/avatar.jpg",
                    'user_type' => $this->user_type,
                    'balance' => $this->vcard_ref->balance,
                    'max_debit' => $this->vcard_ref->max_debit,
                    'count_transactions' => $this->vcard_ref->transactions()->count()
                ];
                break;
            case 'detailedAdmin':
                    return [
                        'id' => $this->id,
                        'username' => $this->username,
                        'name' => $this->name,
                        'email' => $this->email,
                        'photo_url' => $this->photo_url != null ? "/storage/fotos/" . $this->photo_url : "/storage/fotos/avatar.jpg",
                        'user_type' => $this->user_type,
                        'count_transactions' => Transaction::withTrashed()->get()->count(),
                        'count_today_transactions' => Transaction::withTrashed()->whereDate('created_at', Carbon::today())->get()->count(),
                        'count_month_transactions' => Transaction::withTrashed()->whereDate('created_at', '>', \Carbon\Carbon::now()->subMonth())->get()->count(),
                        'count_vcards' => VCard::all()->count(),
                    ];
                break;
            case 'vCardAdminList':
                if ($this->user_type == "A") {
                    return [
                        'id' => $this->id,
                        'username' => $this->username,
                        'name' => $this->name,
                        'email' => $this->email,
                        'photo_url' => $this->photo_url != null ? "/storage/fotos/" . $this->photo_url : "/storage/fotos/avatar.jpg",
                        'user_type' => $this->user_type,
                        'deleted' => $this->deleted_at != null ? 1 : 0,
                    ];
                }else {
                    return [
                        'id' => $this->id,
                        'username' => $this->username,
                        'name' => $this->name,
                        'email' => $this->email,
                        'balance' => vCard::withTrashed()->find($this->username)->balance,
                        'photo_url' => $this->photo_url != null ? "/storage/fotos/" . $this->photo_url : "/storage/fotos/avatar.jpg",
                        'user_type' => $this->user_type,
                        'blocked'   => $this->blocked,
                        'max_debit' => $this->max_debit ?? -1,
                        'deleted' => $this->deleted_at != null ? 1 : 0,
                    ];
                }
                break;
            default:
                return [
                    'id' => $this->id,
                    'username' => $this->username,
                    'name' => $this->name,
                    'email' => $this->email,
                    'photo_url' => $this->photo_url != null ? "/storage/fotos/" . $this->photo_url : "/storage/fotos/avatar.jpg",
                    'user_type' => $this->user_type,
                    'blocked'   => $this->blocked
                ];
        }
    }
}
