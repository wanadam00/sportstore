<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $rules = [
            'address1' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postcode' => 'required|integer',
            'country_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
        ];

        $inputs = $request->all();

        // Validate the request data
        $validate = Validator::make($inputs, $rules)->validateWithBag('createOrder');

        // dd($validate);

        $user = $request->user();
        $carts = $inputs['carts'] ?? [];
        $products = $inputs['products'] ?? [];

        // dd($inputs);

        $mergedData = [];

        foreach ($carts as $cartItem) {
            foreach ($products as $product) {
                if ($cartItem["product_id"] == $product["id"]) {
                    $priceToUse = $product['promo_price'] > 0 ? $product['promo_price'] : $product['price'];

                    $mergedData[] = array_merge($cartItem, [
                        "name" => $product["name"],
                        'price' => $priceToUse
                    ]);
                }
            }
        }

        if (empty($mergedData)) {
            return redirect()->back()->with('error', 'No items to purchase.');
        }

        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        $lineItems = [];
        foreach ($mergedData as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => ucwords(strtolower($item['name'])),
                    ],
                    'unit_amount' => (int)($item['price'] * 100),
                ],
                'quantity' => $item['quantity'],
            ];
        }

        if (empty($lineItems)) {
            return redirect()->back()->with('error', 'No valid line items for payment.');
        }

        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);

        // Determine validation rules based on the user's address existence
        // $hasAddress = $user->user_address()->exists();
        // $rules = [
        //     'address1' => 'required|string|max:255',
        //     'city' => 'required|string|max:255',
        //     'state' => 'required|string|max:255',
        //     'postcode' => 'required|integer',
        //     'country_name' => 'required|string|max:255',
        //     'phone_number' => 'required|string|max:255',
        // ];

        // $inputs = $request->all();

        // // Validate the request data
        // Validator::make($inputs, $rules)->validateWithBag('createOrder');

        // if ($validator->fails()) {
        //     return redirect()->back()->withErrors($validator)->withInput();
        // }

        // $newAddress = $inputs['address'] ?? [];
        // dd($newAddress);
        if (!empty($inputs['address1'])) {
            $address = UserAddress::where('isMain', 1)->count();
            if ($address > 0) {
                UserAddress::where('isMain', 1)->update(['isMain' => 0]);
            }
            $address = new UserAddress();
            $address->address1 = $inputs['address1'];
            $address->state = $inputs['state'];
            $address->postcode = $inputs['postcode'];
            $address->city = $inputs['city'];
            $address->country_name = $inputs['country_name'];
            $address->phone_number = $inputs['phone_number'];
            $address->user_id = $user->id;
            $address->save();
        }

        $mainAddress = $user->user_address()->where('isMain', 1)->first();
        if ($mainAddress) {
            $order = new Order();
            $order->status = 'unpaid';
            $order->total_price = $inputs['total'];
            $order->session_id = $checkout_session->id;
            $order->created_by = $user->id;
            $order->user_address_id = $mainAddress->id;
            $order->save();

            $cartItems = CartItem::where(['user_id' => $user->id])->get();
            foreach ($cartItems as $cartItem) {
                $priceToUse = $cartItem->product->promo_price > 0 ? $cartItem->product->promo_price : $cartItem->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $priceToUse,
                ]);

                $cartItem->delete();
            }

            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $item) {
                unset($item);
            }
            array_splice($cartItems, 0, count($cartItems));
            Cart::setCookieCartItems($cartItems);

            $paymentData = [
                'order_id' => $order->id,
                'amount' => $request->total,
                'status' => 'pending',
                'type' => 'stripe',
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ];

            Payment::create($paymentData);
        }

        return Inertia::location($checkout_session->url);
    }

    public function retryPayment(Request $request, $orderId)
    {
        $user = $request->user();
        $order = Order::where('id', $orderId)
            ->where('status', 'unpaid')
            ->where('created_by', $user->id)
            ->first();

        if (!$order) {
            return redirect()->back()->with('error', 'Order not found or already paid.');
        }

        // Retrieve order items
        $orderItems = $order->orderItems; // Assuming you have a relationship set up in your Order model
        $lineItems = [];

        foreach ($orderItems as $item) {
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'myr',
                    'product_data' => [
                        'name' => ucwords(strtolower($item->product->name)),
                    ],
                    'unit_amount' => (int)($item->unit_price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Stripe payment session
        $stripe = new \Stripe\StripeClient(env('STRIPE_KEY'));
        $checkout_session = $stripe->checkout->sessions->create([
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('checkout.cancel'),
        ]);

        // Update the order with the new session ID for tracking
        $order->session_id = $checkout_session->id;
        $order->save();

        return Inertia::location($checkout_session->url);
    }


    public function success(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_KEY'));
        $sessionId = $request->get('session_id');
        try {
            $session = \Stripe\Checkout\Session::retrieve($sessionId);
            if (!$session) {
                throw new NotFoundHttpException;
            }
            $order = Order::where('session_id', $session->id)->first();
            if (!$order) {
                throw new NotFoundHttpException();
            }
            if ($order->status === 'unpaid') {
                $order->status = 'paid';
                $order->save();

                // Decrease product quantity for each order item
                foreach ($order->orderItems as $orderItem) {
                    $product = Product::find($orderItem->product_id);
                    if ($product) {
                        $product->quantity -= $orderItem->quantity; // Reduce quantity
                        $product->save();
                    }
                }
            }

            return redirect()->route('welcome');
        } catch (\Exception $e) {
            throw new NotFoundHttpException();
        }
    }

    public function cancel(Request $request)
    {
        // Logic to handle cancellation
        // You can add any necessary logic here, such as logging or notifying the user

        // Redirecting back to the checkout page or a specific route
        return redirect()->route('welcome')->with('message', 'Checkout was cancelled.');
    }
}
