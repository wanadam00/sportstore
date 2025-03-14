<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\BrandImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class BrandController extends Controller
{
    public function index()
    {

        // $products = Product::get();
        // return Inertia::render('Product/Index');
        // $products = Product::with('category', 'brand', 'brand_images')->get();
        $brands = Brand::with('brand_images')
            ->filtered()
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        // $categories = Category::get();

        return Inertia::render(
            'Brand/Index',
            [
                // 'products' => $products,
                'brands' => $brands->items(),
                'pagination' => [
                    'current_page' => $brands->currentPage(),
                    'last_page' => $brands->lastPage(),
                    'prev_page_url' => $brands->previousPageUrl(),
                    'next_page_url' => $brands->nextPageUrl(),
                    'total' => $brands->total(), // Add any additional pagination info as needed
                ],
                'filters' => request()->only(['search']), // Pass search filters to the front end
                // 'categories' => $categories
            ]
        );
    }



    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'brand_images.*' => 'nullable', // Validate each image
        ];

        $inputs = $request->all();

        // Create a validator instance
        Validator::make($inputs, $rules)->validateWithBag('createBrand');

        // Check if validation fails
        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $brand = new Brand;
        $brand->name = $inputs['name'];
        $brand->save();

        //check if product has images upload

        if (!empty($inputs['brand_images'])) {
            $brandImages = $inputs['brand_images'];
            foreach ($brandImages as $image) {
                $image = $image['raw'];
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Store the image in the public folder with the unique name
                $image->move('brand_images', $uniqueName);
                // Create a new product image record with the product_id and unique name
                BrandImage::create([
                    'brand_id' => $brand->id,
                    'image' => 'brand_images/' . $uniqueName,
                ]);
            }
        }
        return redirect()->route('brands.index')->with('success', 'Product created successfully.');
    }

    //update
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'brand_images.*' => 'nullable', // Validate each image
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $brand = Brand::findOrFail($id);

        // dd($product);
        $brand->name = $request->name;
        // Check if product images were uploaded
        if ($request->hasFile('brand_images')) {
            $brandImages = $request->file('brand_images');
            // Loop through each uploaded image
            foreach ($brandImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Store the image in the public folder with the unique name
                $image->move('brand_images', $uniqueName);

                // Create a new product image record with the product_id and unique name
                BrandImage::create([
                    'brand_id' => $brand->id,
                    'image' => 'brand_images/' . $uniqueName,
                ]);
            }
        }
        $brand->update();
        return redirect()->back()->with('success', 'Product updated successfully.');
    }

    public function deleteImage($id)
    {
        $image = BrandImage::where('id', $id)->delete();
        return redirect()->route('brands.index')->with('success', 'Image deleted successfully.');
    }

    public function destory($id)
    {
        $brand = Brand::findOrFail($id)->delete();
        return redirect()->route('brands.index')->with('success', 'Product deleted successfully.');
    }
}
