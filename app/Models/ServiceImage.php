<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceImage extends Model
{
    protected $fillable = [
        'service_id',
        'image',];

    function service()
    {
        return $this->belongsTo(service::class);
    }
}
