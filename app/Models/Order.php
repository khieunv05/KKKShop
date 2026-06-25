<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'receiver_name',
        'phone',
        'payment_method',
        'is_paid',
        'status',
        'address',
        'shipping_fee',
        'total_price',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function products(){
        return $this->belongsToMany(Product::class,'order_product','order_id','product_id')->withPivot('quantity', 'price');
    }
}
