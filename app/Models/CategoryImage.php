<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class CategoryImage extends Model
{
    protected $fillable = [
        'category_id',
        'image',
    ];

    protected $appends = [
        'url'
    ];

    function category()
    {
        return $this->belongsTo(Category::class);
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
