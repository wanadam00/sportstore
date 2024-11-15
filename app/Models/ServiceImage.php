<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    protected $fillable = [
        'service_id',
        'image',
    ];

    protected $appends = [
        'url',
    ];

    function service()
    {
        return $this->belongsTo(service::class);
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
