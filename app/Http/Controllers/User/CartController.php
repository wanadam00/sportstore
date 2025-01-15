<?php

namespace App\Http\Controllers\User;

use App\Helper\Cart;
use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CartController extends Controller
{
    public function view(Request $request, Product $product)
    {

        $user = $request->user();
        if ($user) {
            $cartItems = CartItem::where('user_id', $user->id)->get();
            $userAddress = UserAddress::where('user_id', $user->id)->where('isMain', 1)->first();
            // dd($userAddress);
            if ($cartItems->count() > 0) {
                return Inertia::render(
                    'User/CartList',
                    [
                        'cartItems' => $cartItems,
                        'userAddress' => $userAddress
                    ]
                );
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            if (count($cartItems) > 0) {
                $cartItems = new CartResource(Cart::getProductsAndCartItems());
                return  Inertia::render('User/CartList', ['cartItems' => $cartItems]);
            } else {
                return redirect()->back();
            }
        }
    }
    public function store(Request $request, Product $product)
    {
        $quantity = $request->post('quantity', 1);
        $user = $request->user();

        if ($user) {
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->increment('quantity');
            } else {
                CartItem::create([
                    'user_id' => $user->id,
                    'product_id' => $product->id,
                    'quantity' => 1,
                ]);
            }
        } else {
            $cartItems = Cart::getCookieCartItems();
            $isProductExists = false;
            foreach ($cartItems as $item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] += $quantity;
                    $isProductExists = true;
                    break;
                }
            }

            if (!$isProductExists) {
                // Determine the price to use (promo price if available, otherwise regular price)
                $priceToUse = $product->promo_price > 0 ? $product->promo_price : $product->price;

                $cartItems[] = [
                    'user_id' => null,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $priceToUse, // Use the determined price
                ];
            }
            Cart::setCookieCartItems($cartItems);
        }
        // dd($quantity);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
    public function update(Request $request, Product $product)
    {
        $quantity = $request->integer('quantity');
        $user = $request->user();
        if ($user) {
            CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->update(['quantity' => $quantity]);
        } else {
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as &$item) {
                if ($item['product_id'] === $product->id) {
                    $item['quantity'] = $quantity;
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);
        }

        return redirect()->back();
    }
    public function delete(Request $request, Product $product)
    {
        $user = $request->user();

        if ($user) {
            // Delete the cart item for the authenticated user
            $cartItem = CartItem::where(['user_id' => $user->id, 'product_id' => $product->id])->first();
            if ($cartItem) {
                $cartItem->delete();
            }

            // Check if the user's cart is empty
            if (CartItem::where('user_id', $user->id)->count() <= 0) {
                return redirect()->route('welcome')->with('info', 'Your cart is empty');
            } else {
                return redirect()->back()->with('success', 'Item removed successfully');
            }
        } else {
            // Handle guest users
            $cartItems = Cart::getCookieCartItems();
            foreach ($cartItems as $i => &$item) {
                if ($item['product_id'] === $product->id) {
                    array_splice($cartItems, $i, 1);
                    break;
                }
            }
            Cart::setCookieCartItems($cartItems);

            // Check if the cookie cart is empty
            if (count($cartItems) <= 0) {
                return redirect()->route('welcome')->with('info', 'Your cart is empty');
            } else {
                return redirect()->back()->with('success', 'Item removed from cart successfully!');
            }
        }
    }
}
