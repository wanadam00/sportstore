<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\UserAddress;
use App\Models\Service;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Import the DB facade

class OrderController extends Controller
{
    public function index()
    {
        // Fetch all order items with their related order and product
        $orderItems = OrderItem::with(['order', 'product'])->get();

        // Return a view or Inertia response
        // return view('order_items.index', compact('orderItems'));
        // Or for Inertia.js
        return Inertia::render('OrderItems/Index', ['orderItems' => $orderItems]);

        // Fetch all orders with related order items and products
        // $orders = Order::with(['orderItems.product'])
        //     ->get()
        //     ->map(function ($order) {
        //         return [
        //             'id' => $order->id,
        //             'total_price' => (float) $order->total_price, // Cast to float
        //             'status' => $order->status,
        //             'session_id' => $order->session_id,
        //             'user_address_id' => $order->user_address_id,
        //             'order_items' => $order->orderItems->map(function ($orderItem) {
        //                 return [
        //                     'id' => $orderItem->id,
        //                     'product_id' => $orderItem->product_id,
        //                     'quantity' => $orderItem->quantity,
        //                     'unit_price' => (float) $orderItem->unit_price, // Cast to float
        //                     'product' => [
        //                         'name' => $orderItem->product->name ?? 'Unknown Product',
        //                         'description' => $orderItem->product->description ?? '',
        //                     ],
        //                 ];
        //             }),
        //         ];
        //     });

        // // Pass the data to Inertia view
        // return Inertia::render('OrderItems/Index', ['orders' => $orders]);
    }

    public function show($id)
    {
        $order = Order::with(['orderItems.product', 'userAddress'])->findOrFail($id);

        $orderDetails = [
            'order_id' => $order->id,
            'status' => $order->status,
            'total_price' => $order->total_price,
            'user_address' => $order->userAddress->full_address ?? 'N/A', // Adjust based on your address structure
            'items' => $order->orderItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'N/A',
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                ];
            }),
        ];

        return Inertia::render('OrderItems/OrderView', [
            'order' => $orderDetails,
        ]);
    }
}
    //     // Fetch all order items with their related order and product
    //     $orderItems = OrderItem::with(['order', 'product'])
    //         ->select('order_id', 'product_id', DB::raw('count(order_id) as product_count'))
    //         ->groupBy('order_id', 'product_id')
    //         ->get();

    //     // Fetch total prices from the orders table
    //     $orderTotals = DB::table('orders')
    //         ->select('id as order_id', 'total_price') // Adjust 'total_price' to your actual column name
    //         ->get()
    //         ->keyBy('order_id');

    //     // Combine order items with their total prices
    //     $orderItemsWithTotals = $orderItems->map(function ($item) use ($orderTotals) {
    //         $item->total_price = (float) ($orderTotals->get($item->order_id)->total_price ?? 0); // Cast to float
    //         return $item;
    //     });

    //     // Return a view or Inertia response
    //     return Inertia::render('OrderItems/Index', ['orderItems' => $orderItemsWithTotals]);
    // }
