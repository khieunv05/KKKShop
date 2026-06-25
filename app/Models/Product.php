<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    public function categories(){
        return $this->belongsToMany(Category::class,'category_product','product_id','category_id');
    }
    protected $fillable = [
        'name',
        'description',
        'price',
        'old_price',
        'stock',
        'warranty',
        'image',
        'is_active',
    ];
    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }
}
