<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Service;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class UserController extends Controller
{
    // public function __construct()
    // {

    //     $this->middleware('can:isUser')->only(['index']);
    // }

    public function index()
    {
        if (Gate::allows('isUser')) {
            $products = Product::with('brand', 'category', 'product_images')->orderBy('id', 'desc')->limit(8)->get();
            $brands = Brand::with('brand_images')->orderBy('id', 'desc')->get();
            $categories = Category::with('category_images')->orderBy('id', 'desc')->get();
            $services = Service::with('service_images')->orderBy('id', 'desc')->get();
            return Inertia::render('Welcome', [
                'products' => $products,
                'brands' => $brands,
                'categories' => $categories,
                'services' => $services,
                'canLogin' => app('router')->has('login'),
                'canRegister' => app('router')->has('register'),
                'laravelVersion' => Application::VERSION,
                'phpVersion' => PHP_VERSION,
            ]);
        } else {
            $dashboardData = $this->calculateDashboardStats();
            $graphData = $this->getWeeklySalesData();

            return Inertia::render('Dashboard/Dashboard', array_merge($dashboardData, [
                'weeklySalesData' => $graphData, // Include weekly sales data here
            ]));
        }
    }

    private function calculateDashboardStats()
    {
        // Filter only 'paid' orders
        $paidOrders = Order::where('status', 'paid');

        // Total Sales for 'paid' orders
        $totalSales = (clone $paidOrders)->sum('total_price');

        // Sales Growth (Monthly Comparison) for 'paid' orders
        $currentMonthSales = (clone $paidOrders)->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->sum('total_price');

        $previousMonthSales = (clone $paidOrders)->whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->sum('total_price');

        $salesGrowth = $previousMonthSales > 0
            ? (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100
            : null;

        // Total Orders for 'paid' orders
        $totalOrders = (clone $paidOrders)->count();

        // Average Order Value for 'paid' orders
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Sales This Week (from start of the week to now) for 'paid' orders
        $salesThisWeek = (clone $paidOrders)->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('total_price');

        return [
            'totalSales' => round($totalSales, 2),
            'salesGrowth' => $salesGrowth ? round($salesGrowth, 2) : null,
            'averageOrderValue' => round($averageOrderValue, 2),
            'totalOrders' => (int) $totalOrders,
            'salesThisWeek' => round($salesThisWeek, 2), // New key for weekly sales
        ];
    }

    public function getWeeklySalesData()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Starting day of the week (e.g., Monday)
        $endOfWeek = Carbon::now()->endOfWeek(); // Ending day of the week (e.g., Sunday)

        // Fetch daily sales for each day in the current week
        $dailySalesData = Order::where('status', 'paid')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total_sales')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format response for frontend
        return $dailySalesData; // Return the data directly
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

    public function getAllBrands()
    {
        return Brand::with('brand_images')->get(); // Fetch all brands
    }

    public function getAllCategories()
    {
        return Category::with('category_images')->get(); // Fetch all categories
    }

    public function getAllServices()
    {
        return Service::with('service_images')->get(); // Fetch all categories
    }
}
