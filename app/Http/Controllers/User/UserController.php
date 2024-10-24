<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // public function __construct()
    // {

    //     $this->middleware('can:isUser')->only(['index']);
    // }

    public function index()
    {
        if (Gate::allows('isUser')) {
            $products = Product::with('brand', 'category', 'product_images')->orderBy('id','desc')->limit(8)->get();
            return Inertia::render('User/Index', [
            'products'=>$products,
            'canLogin' => app('router')->has('login'),
            'canRegister' => app('router')->has('register'),
            'laravelVersion' => Application::VERSION,
            'phpVersion' => PHP_VERSION,
        ]);
        }
        else {
            return Inertia::render('Dashboard/Dashboard');

        }

    }

    // public function index()
    // {
    //     if (Gate::allows('isUser')) {
    //         $products = Product::with('brand', 'category', 'product_images')->orderBy('id','desc')->limit(8)->get();
    //         return Inertia::render('User/Index', [
    //         'products'=>$products,
    //         'canLogin' => app('router')->has('login'),
    //         'canRegister' => app('router')->has('register'),
    //         'laravelVersion' => Application::VERSION,
    //         'phpVersion' => PHP_VERSION,
    //     ]);
    //     }
    //     elseif (Gate::allows('isGuest')) {
    //         $products = Product::with('brand', 'category', 'product_images')->orderBy('id','desc')->limit(8)->get();
    //         // Render a view for guest users, or perform an action
    //         return Inertia::render('Welcome', [
    //             'products'=>$products,
    //         ]);
    //     }
    //     else {
    //         $products = Product::with('brand', 'category', 'product_images')->orderBy('id','desc')->limit(8)->get();
    //         return Inertia::render('Dashboard', [
    //             'products'=>$products,
    //         ]);

    //     }

    // }
    public function getWelcomeProducts()
    {
        return Product::with('brand', 'category', 'product_images')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();
    }
}
