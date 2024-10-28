<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\CategoryImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {

        // $products = Product::get();
        // return Inertia::render('Product/Index');
        // $products = Product::with('category', 'brand', 'brand_images')->get();
        $categories = Category::with('category_images')->get();
        // $categories = Category::get();

        return Inertia::render(
            'Category/Index',
            [
                // 'products' => $products,
                'categories' => $categories,
                // 'categories' => $categories
            ]
        );
    }



    public function store(Request $request)
    {

        $category = new Category;
        $category->name = $request->name;
        $category->save();

        //check if product has images upload

        if ($request->hasFile('category_images')) {
            $categoryImages = $request->file('category_images');
            foreach ($categoryImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Store the image in the public folder with the unique name
                $image->move('category_images', $uniqueName);
                // Create a new product image record with the product_id and unique name
                CategoryImage::create([
                    'category_id' => $category->id,
                    'image' => 'category_images/' . $uniqueName,
                ]);
            }
        }
        return redirect()->route('categories.index')->with('success', 'Product created successfully.');
    }

    //update
    public function update(Request $request, $id)
    {

        $category = Category::findOrFail($id);

        // dd($product);
        $category->name = $request->name;
        // Check if product images were uploaded
        if ($request->hasFile('category_images')) {
            $categoryImages = $request->file('category_images');
            // Loop through each uploaded image
            foreach ($categoryImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Store the image in the public folder with the unique name
                $image->move('category_images', $uniqueName);

                // Create a new product image record with the product_id and unique name
                CategoryImage::create([
                    'category_id' => $category->id,
                    'image' => 'category_images/' . $uniqueName,
                ]);
            }
        }
        $category->update();
        return redirect()->route('categories.index')->with('success', 'Product updated successfully.');
    }

    public function deleteImage($id)
    {
        $image = CategoryImage::where('id', $id)->delete();
        return redirect()->route('categories.index')->with('success', 'Image deleted successfully.');
    }

    public function destory($id)
    {
        $category = Category::findOrFail($id)->delete();
        return redirect()->route('categories.index')->with('success', 'Product deleted successfully.');
    }
}
