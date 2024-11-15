<?php

namespace App\Http\Controllers\User;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class UserOrderController extends Controller
{
    // public function index()
    // {
    //     $orders = auth()->user()->orders()->with('orderItems')->get();

    //     return Inertia::render('Orders/Index', [
    //         'orders' => $orders,
    //     ]);
    // }

    public function index()
    {
        // Fetch orders for the authenticated user with related order items, products, and product images
        $orders = Order::with(['orderItems.product.product_images', 'userAddress.user'])
            ->whereHas('userAddress', function ($query) {
                $query->where('user_id', auth()->id());
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                // Calculate total price based on promo price or unit price for each item
                $calculatedTotalPrice = $order->orderItems->reduce(function ($total, $item) {
                    // Use promo price if available, otherwise use the regular unit price
                    $priceToUse = $item->product->promo_price > 0 ? $item->product->promo_price : $item->unit_price;
                    return $total + ($priceToUse * $item->quantity);
                }, 0);

                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'total_price' => (float) $calculatedTotalPrice, // Calculated total price
                    'order_date' => $order->created_at->format('Y-m-d'),
                    'estimated_delivery_date' => $order->estimated_delivery_date,
                    'tracking_number' => $order->tracking_number ?? 'N/A',
                    'shipment_status' => $order->shipment_status ?? 'N/A',
                    'user_address' => $order->userAddress->full_address ?? 'N/A',
                    'user_name' => $order->userAddress->user->name ?? 'N/A',
                    'phone_number' => $order->userAddress->phone_number ?? 'N/A',
                    'items' => $order->orderItems->map(function ($item) {
                        return [
                            'product_id' => $item->product_id,
                            'product_name' => $item->product->name ?? 'N/A',
                            'quantity' => $item->quantity,
                            'unit_price' => (float) $item->unit_price,
                            'promo_price' => (float) $item->product->promo_price ?? 0,
                            'product_images' => $item->product->product_images->toArray(),
                        ];
                    }),
                ];
            });

        return Inertia::render('User/Order/Index', [
            'orders' => $orders,
        ]);
    }


    public function show($id)
    {
        // Fetch a specific order for the authenticated user
        $order = Order::with(['orderItems.product', 'userAddress.user'])
            ->where('id', $id)
            ->where('user_id', auth()->id()) // Ensure the order belongs to the authenticated user
            ->firstOrFail();

        $orderDetails = [
            'order_id' => $order->id,
            'status' => $order->status,
            'total_price' => (float) $order->total_price,
            'user_address' => $order->userAddress->full_address ?? 'N/A',
            'phone_number' => $order->userAddress->phone_number ?? 'N/A',
            'user_name' => $order->userAddress->user->name ?? 'N/A',
            'items' => $order->orderItems->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name ?? 'N/A',
                    'quantity' => $item->quantity,
                    'unit_price' => (float) $item->unit_price, // Cast to float
                ];
            }),
        ];

        return Inertia::render('User/Order/Index', [
            'order' => $orderDetails,
        ]);
    }

    public function updateShipment(Request $request, $orderId)
    {
        $validator = Validator::make($request->all(), [
            'shipment_status' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order = Order::findOrFail($orderId);
        $order->estimated_delivery_date = $request->estimated_delivery_date;
        $order->tracking_number = $request->tracking_number;
        $order->shipment_status = $request->shipment_status;
        $order->save();

        return response()->json(['message' => 'Shipment updated successfully'], 200);
    }
}
