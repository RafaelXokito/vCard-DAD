<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VCardResource extends JsonResource
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
        switch (VCardResource::$format) {
            case 'balance':
                return [
                    'balance' => $this->balance,
                ];
                break;
            case 'detailed':
                return [
                    'id' => $this->id,
                    'username' => $this->username,
                    'name' => $this->name,
                    'email' => $this->email,
                    'photo_url' => $this->photo_url != null ? "storage/fotos/" . $this->photo_url : "storage/fotos/avatar.jpg",
                    'user_type' => $this->user_type,
                    'balance' => $this->balance,
                    'max_debit' => $this->max_debit,
                    'count_transactions' => $this->transactions()->count()
                ];
                break;
            default:
                return [
                    'id' => $this->id,
                    'username' => $this->username,
                    'name' => $this->name,
                    'email' => $this->email,
                    'photo_url' => $this->photo_url != null ? "storage/fotos/" . $this->photo_url : "storage/fotos/avatar.jpg",
                    'user_type' => $this->user_type,
                    'max_debit' => $this->max_debit,
                    'blocked'   => $this->blocked
                ];
        }
    }
}
