<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
        switch (self::$format) {
            case 'detailed':
                return [
                    'id'    => $this->id,
                    'vcard' => $this->vcard,
                    'date' => $this->date,
                    'datetime' => $this->datetime,
                    'type' => $this->type,
                    'value' => $this->value,
                    'old_balance' => $this->old_balance,
                    'new_balance' => $this->new_balance,
                    'payment_type' => $this->payment_type,
                    'payment_type_name' => $this->payment_type != null ? $this->paymentType->name : null,
                    'payment_reference' => $this->payment_reference,
                    'pair_transaction' => $this->pair_transaction,
                    'pair_vcard' => $this->pair_vcard,
                    'category' => $this->category_id,
                    'category_name' => $this->category_id != null ? $this->category->name : null,
                    'description' => $this->description,
                ];
                break;
            default:
                return [
                    'id'    => $this->id,
                    'vcard' => $this->vcard,
                    'date' => $this->date,
                    'datetime' => $this->datetime,
                    'type' => $this->type,
                    'value' => $this->value,
                    'old_balance' => $this->old_balance,
                    'new_balance' => $this->new_balance,
                    'payment_type' => $this->payment_type,
                    'payment_reference' => $this->payment_reference,
                    'category' => $this->category_id,
                    'description' => $this->description,
                ];
                break;
        }
    }
}
