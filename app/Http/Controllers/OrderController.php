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
        $orderItems = OrderItem::with(['order', 'product', 'order.userAddress.user'])->get();

        // Return a view or Inertia response
        // return view('order_items.index', compact('orderItems'));
        // Or for Inertia.js
        // return Inertia::render('OrderItems/Index', ['orderItems' => $orderItems]);
        $orderItemsData = $orderItems->map(function ($item) {
            return [
                'order_id' => $item->order_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'product_name' => $item->product->name ?? 'N/A',
                'order_status' => $item->order->status,
                'estimated_delivery_date' => $item->order->estimated_delivery_date,
                'tracking_number' => $item->order->tracking_number,
                'shipment_status' => $item->order->shipment_status,
                'user_name' => $item->order->userAddress->user->name ?? 'N/A', // Access user name
            ];
        });
        // dd($orderItemsData);

        return Inertia::render('OrderItems/Index', [
            'orderItems' => $orderItemsData,
        ]);
    }

    public function show($id)
    {
        $order = Order::with(['orderItems.product', 'userAddress.user'])->findOrFail($id);

        $orderDetails = [
            'order_id' => $order->id,
            'status' => $order->status,
            'total_price' => $order->total_price,
            'user_address' => $order->userAddress->full_address ?? 'N/A', // Adjust based on your address structure
            'user_name' => $order->userAddress && $order->userAddress->user ? $order->userAddress->user->name : 'N/A',
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

    public function updateShipment(Request $request, $orderId)
    {
        $request->validate([
            // 'estimated_delivery_date' => 'required|date', // Ensure this is a valid date
            'tracking_number' => 'required|string|max:255', // Validate tracking number
            'shipment_status' => 'required|string', // Validate shipment status
        ]);

        $order = Order::findOrFail($orderId);
        $order->estimated_delivery_date = $request->estimated_delivery_date;
        $order->tracking_number = $request->tracking_number;
        $order->shipment_status = $request->shipment_status;
        $order->save();

        return back()->with('flash', ['success' => 'Shipment updated successfully']);
    }
}
