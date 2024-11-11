<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Sluggable\SlugOptions;
use Spatie\Sluggable\HasSlug;

class Category extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'slug'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function category_images()
    {
        return $this->hasMany(CategoryImage::class);
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function scopeFiltered($query)
    {
        if ($search = request('search')) {
            $query->where('name', 'LIKE', "%{$search}%");
        }
        return $query;
    }
}
