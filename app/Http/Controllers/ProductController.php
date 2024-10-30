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

class ProductController extends Controller
{
    public function index()
    {

        // $products = Product::get();
        // return Inertia::render('Product/Index');
        $products = Product::with('category', 'brand', 'service', 'product_images')->get();
        $brands = Brand::get();
        $categories = Category::get();
        $services = Service::get();

        return Inertia::render(
            'Product/Index',
            [
                'products' => $products,
                'brands' => $brands,
                'categories' => $categories,
                'services' => $services,
            ]
        );
    }



    public function store(Request $request)
    {

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

    //update
    public function update(Request $request, $id)
    {

        $product = Product::findOrFail($id);

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
