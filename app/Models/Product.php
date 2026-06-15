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
    public function components(){
        return $this->belongsToMany(Product::class,'product_components','parent_id','child_id')->withPivot('quantity');
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
}
