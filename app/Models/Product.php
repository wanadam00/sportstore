<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Builder;

class Product extends Model
{
    use HasSlug;
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'published',
        'inStock',
        'price',
        'promo_price',
        'created_by',
        'updated_by',
        'deleted_by',

    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function product_images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }


    // Filter logic for price, categories, or brands
    public function scopeFiltered(Builder $query)
    {
        $query
            // Filter by product name (search)
            ->when(request('search'), function (Builder $q) {
                $q->where('name', 'LIKE', '%' . request('search') . '%');
            })
            ->when(request('brands'), function (Builder $q) {
                $q->whereIn('brand_id', request('brands'));
            })
            ->when(request('services'), function (Builder $q) {
                $q->whereIn('service_id', request('services'));
            })
            ->when(request('categories'), function (Builder $q) {
                $q->whereIn('category_id', request('categories'));
            })
            ->when(request('prices'), function (Builder $q) {
                $q->whereBetween('price', [
                    request('prices.from', 0),
                    request('prices.to', 100000),
                ]);
            })
            ->when(request('promo_prices'), function (Builder $q) {
                $q->whereBetween('promo_price', [
                    request('promo_prices.from', 0),
                    request('promo_prices.to', 100000),
                ]);
            });

        return $query; // Always return the query for chaining
    }
}
