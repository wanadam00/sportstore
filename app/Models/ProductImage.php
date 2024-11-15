<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'image',
    ];

    protected $appends = [
        'url',
    ];

    function product()
    {
        return $this->belongsTo(Product::class);
    }

    function url(): Attribute
    {
        return Attribute::make(
            get: function () {
                return url($this->image);
            },
            set: function () {}
        );
    }

}
