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
                    'username' => $this->username,
                    'name' => $this->name,
                    'email' => $this->email,
                    'photo_url' => $this->photo_url != null ? "storage/fotos/" . $this->photo_url : "storage/fotos/default_image.png",
                    'user_type' => $this->user_type,
                    'balance' => $this->vcard_ref->balance,
                    'max_debit' => $this->vcard_ref->max_debit,
                    'count_transactions' => $this->vcard_ref->transactions()->count()
                ];
                break;
            case 'detailedAdmin':
                    return [
                        'username' => $this->username,
                        'name' => $this->name,
                        'email' => $this->email,
                        'photo_url' => $this->photo_url != null ? "storage/fotos/" . $this->photo_url : "storage/fotos/default_image.png",
                        'user_type' => $this->user_type,
                        'count_transactions' => Transaction::all()->count(),
                        'count_today_transactions' => Transaction::whereDate('created_at', Carbon::today())->get()->count(),
                        'count_month_transactions' => Transaction::whereDate('created_at', '>', \Carbon\Carbon::now()->subMonth())->get()->count(),
                        'count_vcards' => VCard::all()->count(),
                    ];
                break;
            default:
                return [
                    'username' => $this->username,
                    'name' => $this->name,
                    'email' => $this->email,
                    'photo_url' => $this->photo_url != null ? "storage/fotos/" . $this->photo_url : "storage/fotos/default_image.png",
                    'user_type' => $this->user_type
                ];
        }
    }
}
