<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Service;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {

        // $products = Product::get();
        // return Inertia::render('Product/Index');
        $products = Product::with('category', 'brand', 'service', 'product_images')->filtered()->orderBy('name')->paginate(10);
        $brands = Brand::all();
        $categories = Category::all();
        $services = Service::all();

        return Inertia::render('Product/Index', [
            'products' => $products->items(), // Pass only the data array for the products
            'brands' => $brands,
            'categories' => $categories,
            'services' => $services,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'prev_page_url' => $products->previousPageUrl(),
                'next_page_url' => $products->nextPageUrl(),
                'total' => $products->total(), // Add any additional pagination info as needed
            ],
        ]);
    }



    public function store(Request $request)
    {

        // Validator::make
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'alpha', 'min:0'],
            'promo_price' => ['nullable', 'alpha', 'min:0'],
            'quantity' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'brand_id' => ['required', 'exists:brands,id'],
            'service_id' => ['required', 'exists:services,id'],
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }


        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->promo_price = $request->promo_price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->service_id = $request->service_id;
        $product->save();

        //check if product has images upload

        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            foreach ($productImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Store the image in the public folder with the unique name
                $image->move('product_images', $uniqueName);
                // Create a new product image record with the product_id and unique name
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }
        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function show($id)
    {
        // Fetch the product details
        $product = Product::with('category', 'brand', 'service', 'product_images')->findOrFail($id);
        $brand = Brand::get();
        $category = Category::get();
        $service = Service::get();
        // dd($products);

        // $productDetails = [
        //     'product_id' => $product->id,
        //     'product_name' => $product->name,
        //     'total_price' => $product->total_price,
        //     'user_address' => $product->userAddress->full_address ?? 'N/A', // Adjust based on your address structure
        //     'user_name' => $product->userAddress && $product->userAddress->user ? $product->userAddress->user->name : 'N/A',
        //     'created_at' => $product->created_at,
        // ];
        // Return the view with product details

        return Inertia::render(
            'Product/ProductView',
            [
                'product' => $product,
                'brand' => $brand,
                'category' => $category,
                'service' => $service,
            ]
        );
    }

    //update
    public function update(Request $request, $id)
    {

        $product = Product::findOrFail($id);
        // Validator::make($request->all(), [
        //     'name' => ['required'],
        //     'price' => ['required', 'alpha'],
        //     'promo_price' => ['nullable', 'alpha'],
        //     'quantity' => ['required', 'alpha'],
        //     'description' => ['nullable'],
        //     'category_id' => ['required'],
        //     'brand_id' => ['required'],
        //     'service_id' => ['required'],
        //     'product_images' => ['nullable'],
        // ])->validateWithBag('updateProduct');

        // dd($product);
        $product->name = $request->name;
        $product->price = $request->price;
        $product->promo_price = $request->promo_price;
        $product->quantity = $request->quantity;
        $product->description = $request->description;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;
        $product->service_id = $request->service_id;
        // Check if product images were uploaded
        if ($request->hasFile('product_images')) {
            $productImages = $request->file('product_images');
            // Loop through each uploaded image
            foreach ($productImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Store the image in the public folder with the unique name
                $image->move('product_images', $uniqueName);

                // Create a new product image record with the product_id and unique name
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }
        $product->update();
        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function deleteImage($id)
    {
        $image = ProductImage::where('id', $id)->delete();
        return redirect()->route('products.index')->with('success', 'Image deleted successfully.');
    }

    public function destory($id)
    {
        $product = Product::findOrFail($id)->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }
}
