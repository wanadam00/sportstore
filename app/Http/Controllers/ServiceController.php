<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Category;
use App\Models\ServiceImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index()
    {

        // $products = Product::get();
        // return Inertia::render('Product/Index');
        // $products = Product::with('category', 'brand', 'service_images')->get();
        $services = Service::with('service_images')
            ->filtered()
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();
        // $categories = Category::get();

        return Inertia::render(
            'Service/Index',
            [
                // 'products' => $products,
                'services' => $services->items(),
                'pagination' => [
                    'current_page' => $services->currentPage(),
                    'last_page' => $services->lastPage(),
                    'prev_page_url' => $services->previousPageUrl(),
                    'next_page_url' => $services->nextPageUrl(),
                    'total' => $services->total(), // Add any additional pagination info as needed
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
            'service_images.*' => 'nullable', // Validate each image
        ];

        $inputs = $request->all();

        // Create a validator instance
        Validator::make($inputs, $rules)->validateWithBag('createService');

        // Check if validation fails
        // if ($validator->fails()) {
        //     return redirect()->back()
        //         ->withErrors($validator)
        //         ->withInput();
        // }

        $service = new Service;
        $service->name = $inputs['name'];
        $service->save();

        //check if product has images upload

        if (!empty($inputs['service_images'])) {
            $serviceImages = $inputs['service_images'];
            foreach ($serviceImages as $image) {
                $image = $image['raw'];
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                // Store the image in the public folder with the unique name
                $image->move('service_images', $uniqueName);
                // Create a new product image record with the product_id and unique name
                ServiceImage::create([
                    'service_id' => $service->id,
                    'image' => 'service_images/' . $uniqueName,
                ]);
            }
        }
        return redirect()->route('services.index')->with('success', 'Product created successfully.');
    }

    //update
    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'service_images.*' => 'nullable', // Validate each image
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $service = Service::findOrFail($id);

        // dd($product);
        $service->name = $request->name;
        // Check if product images were uploaded
        if ($request->hasFile('service_images')) {
            $serviceImages = $request->file('service_images');
            // Loop through each uploaded image
            foreach ($serviceImages as $image) {
                // Generate a unique name for the image using timestamp and random string
                $uniqueName = time() . '-' . Str::random(10) . '.' . $image->getClientOriginalExtension();

                // Store the image in the public folder with the unique name
                $image->move('service_images', $uniqueName);

                // Create a new product image record with the product_id and unique name
                ServiceImage::create([
                    'service_id' => $service->id,
                    'image' => 'service_images/' . $uniqueName,
                ]);
            }
        }
        $service->update();
        return redirect()->route('services.index')->with('success', 'Product updated successfully.');
    }

    public function deleteImage($id)
    {
        $image = ServiceImage::where('id', $id)->delete();
        return redirect()->route('services.index')->with('success', 'Image deleted successfully.');
    }

    public function destory($id)
    {
        $service = Service::findOrFail($id)->delete();
        return redirect()->route('services.index')->with('success', 'Product deleted successfully.');
    }
}
