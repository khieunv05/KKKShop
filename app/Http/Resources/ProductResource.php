<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => (float) $this->price,
            'old_price' => $this->old_price !== null ? (float) $this->old_price : null,
            'stock' => $this->stock,
            'warranty' => $this->warranty,
            'image' => $this->image,
            'is_active' => (bool) $this->is_active,
            'is_builder' => (bool) $this->is_builder,
            'categories' => $this->whenLoaded('categories')->map(function ($c) {
                return ['id' => $c->id, 'name' => $c->name];
            }),
            'components' => $this->whenLoaded('components')->map(function ($p) {
                return ['id' => $p->id, 'name' => $p->name, 'pivot_quantity' => $p->pivot->quantity ?? null];
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
