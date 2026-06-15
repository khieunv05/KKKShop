<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function products(){
        return $this->belongsToMany(Product::class,'category_product','product_id','category_id');
    }
    protected $fillable = [
        'name',
        'description',
    ];
}
