<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductListController extends Controller
{
    public function list()
    {
        $products = Product::with('category', 'brand', 'product_images')
            ->filtered()
            ->orderBy('name')
            ->paginate(9)
            ->withQueryString();
        // $filterProducts = $products->filtered()->paginate(20)->withQueryString();

        $categories = Category::all();
        $brands = Brand::all();
        // dd($brands);

        return Inertia::render(
            'User/ProductList',
            [
                'categories' => $categories,
                'brands' => $brands,
                'products' => ProductResource::collection($products),
                'pagination' => [
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'prev_page_url' => $products->previousPageUrl(),
                    'next_page_url' => $products->nextPageUrl(),
                ],
                'filters' => request()->only(['search']),
            ]
        );
    }

    // public function category()
    // {
    //     // Retrieve the product along with its relationships
    //     // $products = Product::with('category', 'brand', 'product_images')->findOrFail($id);
    //     // $filterProducts = $products->filtered()->paginate(9)->withQueryString();

    //     // $categories = Category::get();
    //     // $brands = Brand::get();

    //     // return Inertia::render('User/ProductView', [
    //     //     'categories' => $categories,
    //     //     'brands' => $brands,
    //     //     'products' => ProductResource::collection($filterProducts)
    //     // ]);
    //     $products = Product::with('category', 'brand', 'product_images', 'category_images'); // Fetch the product by ID
    //     $categories = Category::with('product', 'brand', 'product_images', 'category_images')->get();
    //     $brands = Brand::get();
    //     return Inertia::render('User/Layouts/Hero', [
    //         'products' => $products,
    //         'categories' => $categories,
    //         'brands' => $brands,
    //     ]);
    // }

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
        $products = Product::with('category', 'brand', 'product_images')->findOrFail($id); // Fetch the product by ID
        $categories = Category::get();
        $brands = Brand::get();
        return Inertia::render('User/ProductView', [
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
        ]);
    }

    public function contact()
    {
        // $feedbacks = new Feedback;
        // $feedbacks->email = $request->email;
        // $feedbacks->message = $request->message;
        // $feedbacks->save();

        // return redirect()->route('products.contact')->with('success', 'Product created successfully.');

        $feedbacks = Feedback::get();
        return Inertia::render('User/Components/Contact', [
            'feedbacks' => $feedbacks,
        ]);
    }

    public function store(Request $request)
    {
        $feedbacks = new Feedback;
        $feedbacks->email = $request->email;
        $feedbacks->message = $request->message;
        $feedbacks->save();

        return redirect()->back()->with('success', 'Product created successfully.');

        // $feedbacks = Feedback::get();
        // return Inertia::render('User/Components/Contact', [
        //     'feedbacks' => $feedbacks,
        // ]);
    }
}
