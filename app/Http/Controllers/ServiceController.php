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

class ServiceController extends Controller
{
    public function index()
    {

        // $products = Product::get();
        // return Inertia::render('Product/Index');
        // $products = Product::with('category', 'brand', 'service_images')->get();
        $services = Service::with('service_images')->get();
        // $categories = Category::get();

        return Inertia::render(
            'Service/Index',
            [
                // 'products' => $products,
                'services' => $services,
                // 'categories' => $categories
            ]
        );
    }



    public function store(Request $request)
    {

        $service = new Service;
        $service->name = $request->name;
        $service->save();

        //check if product has images upload

        if ($request->hasFile('service_images')) {
            $serviceImages = $request->file('service_images');
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
        return redirect()->route('services.index')->with('success', 'Product created successfully.');
    }

    //update
    public function update(Request $request, $id)
    {

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