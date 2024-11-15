<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class BrandImage extends Model
{
    protected $fillable = [
        'brand_id',
        'image',
    ];

    protected $appends = [
        'url'
    ];

    function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    function url(): Attribute
    {
        return Attribute::make(
            get: function () {
                return url($this->image);
            },
        );
    }
}
