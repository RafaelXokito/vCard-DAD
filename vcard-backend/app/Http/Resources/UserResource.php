<?php

namespace App\Http\Resources;

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
            case 'detailed':
                return parent::toArray($request);
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
