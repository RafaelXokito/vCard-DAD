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
            default:
                return parent::toArray($request);
        }
    }
}
