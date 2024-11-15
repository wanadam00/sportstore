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
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        // Fetch paginated order items ordered by 'created_at' in descending order
        $orderItems = OrderItem::with(['order', 'product', 'order.userAddress.user'])
            ->filtered()
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Map the paginated items to the desired structure
        $orderItemsData = $orderItems->getCollection()->map(function ($item) {
            return [
                'id' => $item->id,
                'order_id' => $item->order_id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
                'product_name' => $item->product->name ?? 'N/A',
                'order_status' => $item->order->status,
                'estimated_delivery_date' => $item->order->estimated_delivery_date,
                'tracking_number' => $item->order->tracking_number,
                'shipment_status' => $item->order->shipment_status,
                'user_name' => $item->order->userAddress->user->name ?? 'N/A',
                'created_at' => $item->created_at,
            ];
        });

        // Update the collection in the paginated result
        $orderItems->setCollection($orderItemsData);

        return Inertia::render('OrderItems/Index', [
            'orderItems' => $orderItemsData,
            'pagination' => [
                'current_page' => $orderItems->currentPage(),
                'last_page' => $orderItems->lastPage(),
                'prev_page_url' => $orderItems->previousPageUrl(),
                'next_page_url' => $orderItems->nextPageUrl(),
                'total' => $orderItems->total(),
            ],
            'filters' => request()->only(['search']),
        ]);
    }



    public function show($id)
    {
        $order = Order::with(['orderItems.product', 'userAddress.user'])->findOrFail($id);
        // Calculate the total price based on promo price or unit price for each item
        $calculatedTotalPrice = $order->orderItems->reduce(function ($total, $item) {
            // Use promo price if available, otherwise use the regular unit price
            $priceToUse = $item->product->promo_price > 0 ? $item->product->promo_price : $item->unit_price;
            return $total + ($priceToUse * $item->quantity);
        }, 0);

        $orderDetails = [
            'order_id' => $order->id,
            'status' => $order->status,
            'total_price' => (float) $calculatedTotalPrice,
            'user_address' => $order->userAddress->full_address ?? 'N/A',
            'phone_number' => $order->userAddress->phone_number ?? 'N/A',
            'user_name' => $order->userAddress && $order->userAddress->user ? $order->userAddress->user->name : 'N/A',
            'created_at' => $order->created_at,
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
        // $request->validate([
        //     'estimated_delivery_date' => 'required_if:shipment_status,shipped|date', // Required if status is 'shipped'
        //     'tracking_number' => 'required|string|max:255', // Validate tracking number
        //     'shipment_status' => 'required|string', // Validate shipment status
        // ]);
        $rules = [
            // 'estimated_delivery_date' => 'required_if:shipment_status,shipped|date', // Required if status is 'shipped'
            'tracking_number' => 'required|string|max:255', // Validate tracking number
            'shipment_status' => 'required|string', // Validate shipment status
        ];
        $shipmentStatus = $request->input('shipment_status');
        // Add conditional rule for estimated_delivery_date only if the status is "shipped"
        if ($shipmentStatus === 'shipped' || $shipmentStatus === 'delivered') {
            $rules['estimated_delivery_date'] = 'required|date';
        }

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $order = Order::findOrFail($orderId);
        $order->estimated_delivery_date = $request->estimated_delivery_date;
        $order->tracking_number = $request->tracking_number;
        $order->shipment_status = $request->shipment_status;
        $order->save();

        return redirect()->back()->with('success', 'Shipment updated successfully');
    }
}
