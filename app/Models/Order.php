<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_price',
        'status',
        'session_id',
        'user_address_id',
        'created_by',
        'updated_by',
        'estimated_delivery_date',
        'tracking_number',
        'shipment_status',
    ];

    function order_items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Define relationship to OrderItem
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class); // 'order_id' is the foreign key in the order_items table
    }

    public function userAddress()
    {
        return $this->belongsTo(UserAddress::class);
    }

    public function scopeFiltered($query)
    {
        if ($search = request('search')) {
            $query->where('id', 'LIKE', "%{$search}%");
        }
        return $query;
    }
}
