<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'product_id',
        'quantity',
        'unit_price'
    ];

    function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }

}
