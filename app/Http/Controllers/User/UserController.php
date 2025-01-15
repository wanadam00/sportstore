<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Service;
use App\Models\User;
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
        $paidOrders = Order::where('status', 'paid');

        // Total Sales
        $totalSales = (clone $paidOrders)->sum('total_price');

        // Current Month Sales
        $currentMonthSales = (clone $paidOrders)->whereBetween('updated_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->sum('total_price');

        // Previous Month Sales
        $previousMonthSales = (clone $paidOrders)->whereBetween('updated_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->sum('total_price');

        // Calculate Sales Growth
        if ($currentMonthSales > 0 && $previousMonthSales > 0) {
            $salesGrowth = (($currentMonthSales - $previousMonthSales) / $previousMonthSales) * 100;
        } elseif ($currentMonthSales == 0 && $previousMonthSales > 0) {
            $salesGrowth = -100; // Explicitly set to -100% for no current sales
        } elseif ($previousMonthSales == 0) {
            $salesGrowth = $currentMonthSales > 0 ? 100 : null; // 100% growth if sales start, null otherwise
        } else {
            $salesGrowth = null; // Default case for no data
        }

        // Total Orders
        $totalOrders = (clone $paidOrders)->count();

        // Average Order Value
        $averageOrderValue = $totalOrders > 0 ? $totalSales / $totalOrders : 0;

        // Sales This Week
        $salesThisWeek = (clone $paidOrders)->whereBetween('updated_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->sum('total_price');

        return [
            'totalSales' => round($totalSales, 2),
            'salesGrowth' => $salesGrowth !== null ? round($salesGrowth, 2) : null,
            'averageOrderValue' => round($averageOrderValue, 2),
            'totalOrders' => (int) $totalOrders,
            'salesThisWeek' => round($salesThisWeek, 2),
        ];
    }

    public function getWeeklySalesData()
    {
        $startOfWeek = Carbon::now()->startOfWeek(); // Starting day of the week (e.g., Monday)
        $endOfWeek = Carbon::now()->endOfWeek(); // Ending day of the week (e.g., Sunday)

        // Fetch daily sales for each day in the current week
        $dailySalesData = Order::where('status', 'paid')
            ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
            ->selectRaw('DATE(updated_at) as date, SUM(total_price) as total_sales')
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Format response for frontend
        return $dailySalesData; // Return the data directly
    }

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

    public function user()
    {
        return redirect()->route('profile.user');
    }

    public function store(Request $request)
    {
        $user = User::create($request->all());

        // Check if the user should be assigned an admin role
        if ($request->has('is_admin') && $request->is_admin) {
            $user->assignRole('admin');
        }

        return redirect()->route('users.index');
    }
    public function promoteToAdmin($userId)
    {
        $user = User::find($userId);
        if ($user) {
            $user->assignRole('admin');
        }

        return redirect()->back()->with('message', 'User promoted to admin!');
    }
}
