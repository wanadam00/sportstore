<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'address1',
        'address2',
        'city',
        'state',
        'postcode',
        'isMain',
        'contry_code',
        'contry_name',
        'user_id',
        'type',
        'phone_number',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFullAddressAttribute()
    {
        return "{$this->address1}<br>" .
               ($this->address2 ? "{$this->address2}<br>" : "") .
               "{$this->city} {$this->state}<br>{$this->postcode} {$this->country_name}";
    }

}
