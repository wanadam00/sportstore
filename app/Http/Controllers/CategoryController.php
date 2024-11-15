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
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {

        // $products = Product::get();
        // return Inertia::render('Product/Index');
        // $products = Product::with('category', 'brand', 'brand_images')->get();
        $categories = Category::with('category_images')
            ->filtered()
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        // $categories = Category::get();

        return Inertia::render(
            'Category/Index',
            [
                // 'products' => $products,
                'categories' => $categories->items(),
                'pagination' => [
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'prev_page_url' => $categories->previousPageUrl(),
                    'next_page_url' => $categories->nextPageUrl(),
                    'total' => $categories->total(), // Add any additional pagination info as needed
                ],
                // 'categories' => $categories
                'filters' => request()->only(['search']),
            ]
        );
    }



    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'category_images.*' => 'nullable', // Validate each image
        ];

        $inputs = $request->all();

        // Create a validator instance
        Validator::make($inputs, $rules)->validateWithBag('createCategory');

        // Check if validation fails
        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $category = new Category;
        $category->name = $inputs['name'];
        $category->save();

        //check if product has images upload

        if (!empty($inputs['category_images'])) {
            $categoryImages = $inputs['category_images'];
            foreach ($categoryImages as $image) {
                $image = $image['raw'];
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
        $rules = [
            'name' => 'required|string|max:255',
            'category_images.*' => 'nullable', // Validate each image
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

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
