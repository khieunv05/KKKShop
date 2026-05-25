<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'total' => (float) $this->total,
            'status' => $this->status,
            'shipping_address' => $this->shipping_address,
            'phone' => $this->phone,
            'payment_method' => $this->payment_method,
            'items' => $this->whenLoaded('items')->map(function ($i) {
                return [
                    'product' => ['id' => $i->product->id, 'name' => $i->product->name],
                    'price' => (float) $i->price,
                    'quantity' => $i->quantity,
                    'total' => (float) $i->total,
                ];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
