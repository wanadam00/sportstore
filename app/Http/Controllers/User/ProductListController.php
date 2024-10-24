<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductListController extends Controller
{
    public function list()
    {
        $products = Product::with('category', 'brand', 'product_images');
        $filterProducts = $products->filtered()->paginate(9)->withQueryString();

        $categories = Category::get();
        $brands = Brand::get();

        return Inertia::render(
            'User/ProductList',
            [
                'categories' => $categories,
                'brands' => $brands,
                'products' => ProductResource::collection($filterProducts)
            ]
        );
    }

    public function view($id)
    {
        // Retrieve the product along with its relationships
        // $products = Product::with('category', 'brand', 'product_images')->findOrFail($id);
        // $filterProducts = $products->filtered()->paginate(9)->withQueryString();

        // $categories = Category::get();
        // $brands = Brand::get();

        // return Inertia::render('User/ProductView', [
        //     'categories' => $categories,
        //     'brands' => $brands,
        //     'products' => ProductResource::collection($filterProducts)
        // ]);
        $product = Product::findOrFail($id); // Fetch the product by ID
    return Inertia::render('User/ProductView', [
        'product' => $product,
    ]);
    }
}
